<h5 class="mb-3">Информация о договоре и финансах</h5>
<div class="row">
    <div class="col-md-6">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th width="40%">Дата договора:</th>
                    <td>{{ $project->contract_date ? $project->contract_date->format('d.m.Y') : 'Не указана' }}</td>
                </tr>
                <tr>
                    <th>Номер договора:</th>
                    <td>{{ $project->contract_number ?? 'Не указан' }}</td>
                </tr>
                <tr>
                    <th>Дата начала работ:</th>
                    <td>{{ $project->work_start_date ? $project->work_start_date->format('d.m.Y') : 'Не указана' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th width="40%">Сумма на работы:</th>
                    <td>{{ number_format($project->work_amount, 2, '.', ' ') }} ₽</td>
                </tr>
                <tr>
                    <th>Сумма на материалы:</th>
                    <td>{{ number_format($project->materials_amount, 2, '.', ' ') }} ₽</td>
                </tr>
                <tr>
                    <th>Общая сумма:</th>
                    <td class="fw-bold">{{ number_format($project->total_amount, 2, '.', ' ') }} ₽</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="mb-4">
    <h5>План-график работ и материалов</h5>
    
    @if($project->schedule_link)
        <div class="mb-4 mt-2">
            <div class="d-flex align-items-center mb-2">
                <strong>Ссылка на линейный график:</strong>
                <a href="{{ $project->schedule_link }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                    <i class="fas fa-external-link-alt me-1"></i> Открыть
                </a>
            </div>
        </div>
    @endif

    <!-- Контейнер для уведомлений -->
    <div id="scheduleAlertContainer"></div>

    <!-- Аккордеон для разделов графика -->
    <div class="accordion mt-4" id="scheduleAccordion">
        <!-- Основные работы -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingMainWorks">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMainWorks" aria-expanded="true" aria-controls="collapseMainWorks">
                    Основные работы
                </button>
            </h2>
            <div id="collapseMainWorks" class="accordion-collapse collapse show" aria-labelledby="headingMainWorks">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="55%">Наименование</th>
                                    <th width="15%">Всего</th>
                                    <th width="15%">Оплачено</th>
                                    <th width="10%">Действия</th>
                                </tr>
                            </thead>
                            <tbody id="mainWorksTable">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                            <span class="visually-hidden">Загрузка...</span>
                                        </div>
                                        <span class="ms-2">Загрузка данных...</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <button type="button" class="btn btn-sm btn-success add-item" data-type="main_work">
                                            <i class="fas fa-plus me-1"></i> Добавить
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Основные материалы -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingMainMaterials">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMainMaterials" aria-expanded="false" aria-controls="collapseMainMaterials">
                    Основные материалы
                </button>
            </h2>
            <div id="collapseMainMaterials" class="accordion-collapse collapse" aria-labelledby="headingMainMaterials">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="55%">Наименование</th>
                                    <th width="15%">Всего</th>
                                    <th width="15%">Оплачено</th>
                                    <th width="10%">Действия</th>
                                </tr>
                            </thead>
                            <tbody id="mainMaterialsTable">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                            <span class="visually-hidden">Загрузка...</span>
                                        </div>
                                        <span class="ms-2">Загрузка данных...</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <button type="button" class="btn btn-sm btn-success add-item" data-type="main_material">
                                            <i class="fas fa-plus me-1"></i> Добавить
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Дополнительные работы -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAdditionalWorks">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdditionalWorks" aria-expanded="false" aria-controls="collapseAdditionalWorks">
                    Дополнительные работы
                </button>
            </h2>
            <div id="collapseAdditionalWorks" class="accordion-collapse collapse" aria-labelledby="headingAdditionalWorks">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="55%">Наименование</th>
                                    <th width="15%">Всего</th>
                                    <th width="15%">Оплачено</th>
                                    <th width="10%">Действия</th>
                                </tr>
                            </thead>
                            <tbody id="additionalWorksTable">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                            <span class="visually-hidden">Загрузка...</span>
                                        </div>
                                        <span class="ms-2">Загрузка данных...</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <button type="button" class="btn btn-sm btn-success add-item" data-type="additional_work">
                                            <i class="fas fa-plus me-1"></i> Добавить
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Дополнительные материалы -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAdditionalMaterials">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdditionalMaterials" aria-expanded="false" aria-controls="collapseAdditionalMaterials">
                    Дополнительные материалы
                </button>
            </h2>
            <div id="collapseAdditionalMaterials" class="accordion-collapse collapse" aria-labelledby="headingAdditionalMaterials">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="55%">Наименование</th>
                                    <th width="15%">Всего</th>
                                    <th width="15%">Оплачено</th>
                                    <th width="10%">Действия</th>
                                </tr>
                            </thead>
                            <tbody id="additionalMaterialsTable">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                            <span class="visually-hidden">Загрузка...</span>
                                        </div>
                                        <span class="ms-2">Загрузка данных...</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <button type="button" class="btn btn-sm btn-success add-item" data-type="additional_material">
                                            <i class="fas fa-plus me-1"></i> Добавить
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Транспортировка -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTransportation">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTransportation" aria-expanded="false" aria-controls="collapseTransportation">
                    Транспортировка
                </button>
            </h2>
            <div id="collapseTransportation" class="accordion-collapse collapse" aria-labelledby="headingTransportation">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="55%">Наименование</th>
                                    <th width="15%">Всего</th>
                                    <th width="15%">Оплачено</th>
                                    <th width="10%">Действия</th>
                                </tr>
                            </thead>
                            <tbody id="transportationTable">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                            <span class="visually-hidden">Загрузка...</span>
                                        </div>
                                        <span class="ms-2">Загрузка данных...</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <button type="button" class="btn btn-sm btn-success add-item" data-type="transportation">
                                            <i class="fas fa-plus me-1"></i> Добавить
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления/редактирования элемента -->
<div class="modal fade" id="scheduleItemModal" tabindex="-1" aria-labelledby="scheduleItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleItemModalLabel">Добавление элемента</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleItemForm">
                    <input type="hidden" id="scheduleItemType" name="type">
                    <input type="hidden" id="scheduleItemId" name="id">
                    
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Наименование</label>
                        <input type="text" class="form-control" id="itemName" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="itemTotal" class="form-label">Всего (₽)</label>
                        <input type="number" class="form-control" id="itemTotal" name="total_amount" min="0" step="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="itemPaid" class="form-label">Оплачено (₽)</label>
                        <input type="number" class="form-control" id="itemPaid" name="paid_amount" min="0" step="0.01" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="saveScheduleItem">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript для управления элементами плана-графика -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация переменных для работы с модальным окном
        let modal;
        const modalElement = document.getElementById('scheduleItemModal');
        const form = document.getElementById('scheduleItemForm');
        const saveButton = document.getElementById('saveScheduleItem');
        const typeInput = document.getElementById('scheduleItemType');
        const idInput = document.getElementById('scheduleItemId');
        const projectId = {{ $project->id }};
        
        // Полностью отключаем стандартное поведение Bootstrap для аккордеона
        // и реализуем собственное управление
        const accordionButtons = document.querySelectorAll('.accordion-button');
        accordionButtons.forEach(button => {
            // Удаляем стандартные обработчики Bootstrap
            button.removeAttribute('data-bs-toggle');
            
            // Получаем целевой элемент collapse
            const targetId = button.getAttribute('data-bs-target');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                // Очищаем все существующие обработчики
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                
                // Добавляем новый обработчик события
                newButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Переключаем состояние collapse
                    const isExpanded = targetElement.classList.contains('show');
                    
                    if (isExpanded) {
                        targetElement.classList.remove('show');
                        newButton.classList.add('collapsed');
                        newButton.setAttribute('aria-expanded', 'false');
                    } else {
                        targetElement.classList.add('show');
                        newButton.classList.remove('collapsed');
                        newButton.setAttribute('aria-expanded', 'true');
                    }
                });
                
                // Устанавливаем начальное состояние
                if (targetElement.classList.contains('show')) {
                    newButton.setAttribute('aria-expanded', 'true');
                    newButton.classList.remove('collapsed');
                } else {
                    newButton.setAttribute('aria-expanded', 'false');
                    newButton.classList.add('collapsed');
                }
            }
        });
        
        // Инициализация Bootstrap Modal
        if (typeof bootstrap !== 'undefined') {
            modal = new bootstrap.Modal(modalElement);
        } else {
            console.error('Bootstrap не определен');
        }
        
        // Загрузка данных с сервера
        loadAllItems();
        
        // Обработчик кнопок добавления нового элемента
        document.querySelectorAll('.add-item').forEach(button => {
            button.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                
                // Сброс формы и установка типа
                form.reset();
                typeInput.value = type;
                idInput.value = '';
                
                // Установка заголовка
                document.getElementById('scheduleItemModalLabel').textContent = 'Добавление элемента';
                
                // Отображение модального окна
                modal.show();
            });
        });
        
        // Обработчик сохранения элемента
        saveButton.addEventListener('click', function() {
            if (form.checkValidity()) {
                const formData = {
                    name: document.getElementById('itemName').value,
                    total_amount: document.getElementById('itemTotal').value,
                    paid_amount: document.getElementById('itemPaid').value
                };
                
                // Определяем, это создание или редактирование
                const itemId = idInput.value;
                
                if (itemId) {
                    // Редактирование существующего элемента
                    updateItem(itemId, formData);
                } else {
                    // Создание нового элемента
                    formData.type = typeInput.value;
                    createItem(formData);
                }
            } else {
                form.reportValidity();
            }
        });
        
        // Функция для загрузки всех элементов плана-графика
        function loadAllItems() {
            // Показываем индикаторы загрузки
            document.querySelectorAll('#mainWorksTable, #mainMaterialsTable, #additionalWorksTable, #additionalMaterialsTable, #transportationTable').forEach(table => {
                table.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                <span class="visually-hidden">Загрузка...</span>
                            </div>
                            <span class="ms-2">Загрузка данных...</span>
                        </td>
                    </tr>
                `;
            });
            
            // Запрос к API для получения всех элементов
            fetch(`/partner/projects/${projectId}/schedule/items`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при загрузке данных');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Разбиваем данные по категориям
                        const items = data.data;
                        const categorized = {
                            'main_work': [],
                            'main_material': [],
                            'additional_work': [],
                            'additional_material': [],
                            'transportation': []
                        };
                        
                        // Распределяем элементы по категориям
                        items.forEach(item => {
                            if (categorized[item.type]) {
                                categorized[item.type].push(item);
                            }
                        });
                        
                        // Отображаем данные в таблицах
                        renderTable('mainWorksTable', categorized.main_work);
                        renderTable('mainMaterialsTable', categorized.main_material);
                        renderTable('additionalWorksTable', categorized.additional_work);
                        renderTable('additionalMaterialsTable', categorized.additional_material);
                        renderTable('transportationTable', categorized.transportation);
                    } else {
                        showAlert('Не удалось загрузить данные', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка загрузки данных:', error);
                    showAlert('Произошла ошибка при загрузке данных', 'danger');
                    
                    // Отображаем сообщение об ошибке в таблицах
                    document.querySelectorAll('#mainWorksTable, #mainMaterialsTable, #additionalWorksTable, #additionalMaterialsTable, #transportationTable').forEach(table => {
                        table.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center text-danger">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Не удалось загрузить данные
                                </td>
                            </tr>
                        `;
                    });
                });
        }
        
        // Функция для отрисовки таблицы с данными
        function renderTable(tableId, items) {
            const tbody = document.getElementById(tableId);
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            if (items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Нет элементов</td></tr>';
                return;
            }
            
            // Сортировка по позиции
            items.sort((a, b) => a.position - b.position);
            
            // Создание строк таблицы
            items.forEach((item, index) => {
                const row = document.createElement('tr');
                row.setAttribute('data-item-id', item.id);
                
                const totalFormatted = parseFloat(item.total_amount).toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const paidFormatted = parseFloat(item.paid_amount).toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${escapeHtml(item.name)}</td>
                    <td>${totalFormatted}</td>
                    <td>${paidFormatted}</td>
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
            
            // Добавляем обработчики событий для новых кнопок
            tbody.querySelectorAll('.edit-item').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    showEditModal(itemId);
                });
            });
            
            tbody.querySelectorAll('.delete-item').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    deleteItem(itemId);
                });
            });
        }
        
        // Функция для отображения модального окна с данными для редактирования
        function showEditModal(itemId) {
            // Запрос к API для получения данных элемента
            fetch(`/partner/schedule-items/${itemId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при загрузке данных элемента');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const item = data.data;
                        
                        // Заполняем форму данными
                        typeInput.value = item.type;
                        idInput.value = item.id;
                        document.getElementById('itemName').value = item.name;
                        document.getElementById('itemTotal').value = item.total_amount;
                        document.getElementById('itemPaid').value = item.paid_amount;
                        
                        // Устанавливаем заголовок
                        document.getElementById('scheduleItemModalLabel').textContent = 'Редактирование элемента';
                        
                        // Отображаем модальное окно
                        modal.show();
                    } else {
                        showAlert('Не удалось загрузить данные элемента', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка загрузки данных элемента:', error);
                    showAlert('Произошла ошибка при загрузке данных элемента', 'danger');
                });
        }
        
        // Функция для создания нового элемента
        function createItem(formData) {
            // Запрос к API для создания элемента
            fetch(`/partner/projects/${projectId}/schedule/items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при создании элемента');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Закрываем модальное окно
                        modal.hide();
                        
                        // Отображаем сообщение об успехе
                        showAlert('Элемент успешно добавлен', 'success');
                        
                        // Перезагружаем данные
                        loadAllItems();
                    } else {
                        showAlert(data.message || 'Не удалось добавить элемент', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка создания элемента:', error);
                    showAlert('Произошла ошибка при добавлении элемента', 'danger');
                });
        }
        
        // Функция для обновления существующего элемента
        function updateItem(itemId, formData) {
            // Запрос к API для обновления элемента
            fetch(`/partner/schedule-items/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при обновлении элемента');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Закрываем модальное окно
                        modal.hide();
                        
                        // Отображаем сообщение об успехе
                        showAlert('Элемент успешно обновлен', 'success');
                        
                        // Перезагружаем данные
                        loadAllItems();
                    } else {
                        showAlert(data.message || 'Не удалось обновить элемент', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка обновления элемента:', error);
                    showAlert('Произошла ошибка при обновлении элемента', 'danger');
                });
        }
        
        // Функция для удаления элемента
        function deleteItem(itemId) {
            if (!confirm('Вы уверены, что хотите удалить этот элемент?')) {
                return;
            }
            
            // Запрос к API для удаления элемента
            fetch(`/partner/schedule-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при удалении элемента');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Отображаем сообщение об успехе
                        showAlert('Элемент успешно удален', 'success');
                        
                        // Перезагружаем данные
                        loadAllItems();
                    } else {
                        showAlert(data.message || 'Не удалось удалить элемент', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка удаления элемента:', error);
                    showAlert('Произошла ошибка при удалении элемента', 'danger');
                });
        }
        
        // Функция для отображения уведомлений
        function showAlert(message, type) {
            const alertContainer = document.getElementById('scheduleAlertContainer');
            if (!alertContainer) return;
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            alertContainer.appendChild(alert);
            
            // Автоматическое скрытие через 5 секунд
            setTimeout(() => {
                if (alert.parentNode === alertContainer) {
                    alert.classList.remove('show');
                    setTimeout(() => {
                        if (alert.parentNode === alertContainer) {
                            alertContainer.removeChild(alert);
                        }
                    }, 150);
                }
            }, 5000);
        }
        
        // Функция для экранирования HTML
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    });
</script>
