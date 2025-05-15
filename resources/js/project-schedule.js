/**
 * Скрипт для управления элементами плана-графика работ и материалов
 */

// Класс для управления элементами графика
class ScheduleManager {
    constructor(projectId) {
        this.projectId = projectId;
        this.modal = null;
        this.form = null;
        this.saveButton = null;
        this.currentType = null;
        this.isEditing = false;
        this.currentItemId = null;
        
        this.initModal();
        this.bindEvents();
        this.loadItems();
    }
    
    initModal() {
        this.modal = new bootstrap.Modal(document.getElementById('scheduleItemModal'));
        this.form = document.getElementById('scheduleItemForm');
        this.saveButton = document.getElementById('saveScheduleItem');
    }
    
    bindEvents() {
        // Обработчик кнопок добавления
        document.querySelectorAll('.add-item').forEach(button => {
            button.addEventListener('click', () => this.showAddModal(button.getAttribute('data-type')));
        });
        
        // Обработчик сохранения формы
        this.saveButton.addEventListener('click', () => this.saveItem());
        
        // Делегирование событий для динамически добавленных кнопок редактирования и удаления
        document.addEventListener('click', (event) => {
            // Обработка кнопок редактирования
            if (event.target.closest('.edit-item')) {
                const button = event.target.closest('.edit-item');
                this.showEditModal(button.getAttribute('data-id'));
            }
            
            // Обработка кнопок удаления
            if (event.target.closest('.delete-item')) {
                const button = event.target.closest('.delete-item');
                this.deleteItem(button.getAttribute('data-id'));
            }
        });
    }
    
    // Загрузка всех элементов для проекта
    loadItems() {
        axios.get(`/partner/projects/${this.projectId}/schedule/items`)
            .then(response => {
                if (response.data.success) {
                    this.renderItems(response.data.data);
                }
            })
            .catch(error => {
                console.error('Ошибка загрузки данных:', error);
                this.showAlert('Произошла ошибка при загрузке данных.', 'danger');
            });
    }
    
    // Отрисовка элементов в таблицах
    renderItems(items) {
        // Группировка по типу
        const groupedItems = {
            'main_work': [],
            'main_material': [],
            'additional_work': [],
            'additional_material': [],
            'transportation': []
        };
        
        items.forEach(item => {
            if (groupedItems[item.type]) {
                groupedItems[item.type].push(item);
            }
        });
        
        // Отрисовка каждой группы
        this.renderTable('mainWorksTable', groupedItems.main_work);
        this.renderTable('mainMaterialsTable', groupedItems.main_material);
        this.renderTable('additionalWorksTable', groupedItems.additional_work);
        this.renderTable('additionalMaterialsTable', groupedItems.additional_material);
        this.renderTable('transportationTable', groupedItems.transportation);
    }
    
    // Отрисовка таблицы
    renderTable(tableId, items) {
        const tbody = document.getElementById(tableId);
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        if (items.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center">Нет элементов</td></tr>`;
            return;
        }
        
        // Сортировка по позиции
        items.sort((a, b) => a.position - b.position);
        
        // Создание строк
        items.forEach((item, index) => {
            const row = document.createElement('tr');
            row.setAttribute('data-item-id', item.id);
            
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${this.escapeHtml(item.name)}</td>
                <td>${parseFloat(item.total_amount).toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td>${parseFloat(item.paid_amount).toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary edit-item" data-type="${item.type}" data-id="${item.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger delete-item" data-type="${item.type}" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }
    
    // Показать модальное окно для добавления
    showAddModal(type) {
        this.isEditing = false;
        this.currentType = type;
        this.currentItemId = null;
        
        // Устанавливаем заголовок и тип
        document.getElementById('scheduleItemModalLabel').textContent = 'Добавление элемента';
        document.getElementById('scheduleItemType').value = type;
        
        // Сбрасываем форму
        this.form.reset();
        
        // Показываем модальное окно
        this.modal.show();
    }
    
    // Показать модальное окно для редактирования
    showEditModal(itemId) {
        this.isEditing = true;
        this.currentItemId = itemId;
        
        // Запрос данных элемента
        axios.get(`/partner/schedule-items/${itemId}`)
            .then(response => {
                if (response.data.success) {
                    const item = response.data.data;
                    
                    // Устанавливаем заголовок и данные формы
                    document.getElementById('scheduleItemModalLabel').textContent = 'Редактирование элемента';
                    document.getElementById('scheduleItemType').value = item.type;
                    document.getElementById('itemName').value = item.name;
                    document.getElementById('itemTotal').value = item.total_amount;
                    document.getElementById('itemPaid').value = item.paid_amount;
                    
                    // Показываем модальное окно
                    this.modal.show();
                }
            })
            .catch(error => {
                console.error('Ошибка получения данных элемента:', error);
                this.showAlert('Произошла ошибка при загрузке данных элемента.', 'danger');
            });
    }
    
    // Сохранить элемент (добавить или обновить)
    saveItem() {
        if (!this.form.checkValidity()) {
            this.form.reportValidity();
            return;
        }
        
        const formData = {
            name: document.getElementById('itemName').value,
            total_amount: document.getElementById('itemTotal').value,
            paid_amount: document.getElementById('itemPaid').value
        };
        
        if (!this.isEditing) {
            // Добавление нового элемента
            formData.type = document.getElementById('scheduleItemType').value;
            
            axios.post(`/partner/projects/${this.projectId}/schedule/items`, formData)
                .then(response => {
                    if (response.data.success) {
                        this.modal.hide();
                        this.showAlert('Элемент успешно добавлен.', 'success');
                        this.loadItems();
                    }
                })
                .catch(error => {
                    console.error('Ошибка добавления элемента:', error);
                    this.showAlert('Произошла ошибка при добавлении элемента.', 'danger');
                });
        } else {
            // Обновление существующего элемента
            axios.put(`/partner/schedule-items/${this.currentItemId}`, formData)
                .then(response => {
                    if (response.data.success) {
                        this.modal.hide();
                        this.showAlert('Элемент успешно обновлен.', 'success');
                        this.loadItems();
                    }
                })
                .catch(error => {
                    console.error('Ошибка обновления элемента:', error);
                    this.showAlert('Произошла ошибка при обновлении элемента.', 'danger');
                });
        }
    }
    
    // Удалить элемент
    deleteItem(itemId) {
        if (!confirm('Вы уверены, что хотите удалить этот элемент?')) {
            return;
        }
        
        axios.delete(`/partner/schedule-items/${itemId}`)
            .then(response => {
                if (response.data.success) {
                    this.showAlert('Элемент успешно удален.', 'success');
                    this.loadItems();
                }
            })
            .catch(error => {
                console.error('Ошибка удаления элемента:', error);
                this.showAlert('Произошла ошибка при удалении элемента.', 'danger');
            });
    }
    
    // Показать уведомление
    showAlert(message, type) {
        const alertContainer = document.getElementById('scheduleAlertContainer');
        if (!alertContainer) return;
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Автоматическое скрытие уведомления через 5 секунд
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => {
                alert.remove();
            }, 150);
        }, 5000);
    }
    
    // Экранирование HTML для предотвращения XSS
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const projectIdElement = document.getElementById('projectTabs');
    if (projectIdElement) {
        const projectId = projectIdElement.getAttribute('data-project-id');
        if (projectId) {
            window.scheduleManager = new ScheduleManager(projectId);
        }
    }
});
