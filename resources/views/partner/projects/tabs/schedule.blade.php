<div class="mb-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
        <h5>План график</h5>
        <div>
            @php
                // Расчет временных рамок проекта
                $items = $project->scheduleItems;
                $startDate = $items->min('start_date') ? \Carbon\Carbon::parse($items->min('start_date')) : null;
                $endDate = $items->max('end_date') ? \Carbon\Carbon::parse($items->max('end_date')) : null;
                $daysCount = $startDate && $endDate ? $startDate->diffInDays($endDate) + 1 : 0;
                $weeksCount = $daysCount > 0 ? round($daysCount / 7, 1) : 0;
                $monthsCount = $daysCount > 0 ? round($daysCount / 30, 1) : 0;
            @endphp
            
            @if($startDate && $endDate)
                <div class="text-muted">
                    Срок ремонта: {{ $daysCount }} дней, {{ $weeksCount }} недель, {{ $monthsCount }} месяца
                </div>
            @endif
        </div>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <a href="#" class="btn btn-primary btn-sm me-2" id="exportScheduleBtn">
            <i class="fas fa-download me-1"></i> Скачать
        </a>
        
        @if($project->schedule_link)
            <a href="{{ $project->schedule_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-external-link-alt me-1"></i> Открыть линейный график
            </a>
        @else
            <a href="{{ route('partner.projects.edit', $project) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-link me-1"></i> Указать ссылку на линейный график
            </a>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-light p-3">
        <div class="row g-3 align-items-center">
            <!-- Фильтр по датам -->
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="input-group me-2">
                        <input type="date" class="form-control form-control-sm" id="startDateFilter" placeholder="С">
                        <span class="input-group-text">—</span>
                        <input type="date" class="form-control form-control-sm" id="endDateFilter" placeholder="По">
                        <button class="btn btn-primary btn-sm" id="applyDateFilter">Применить</button>
                    </div>
                </div>
            </div>
            
            <!-- Фильтр по статусу -->
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    <div class="btn-group" role="group">
                        <input type="checkbox" class="btn-check status-filter" id="finishedFilter" checked>
                        <label class="btn btn-sm btn-outline-success" for="finishedFilter">Завершено</label>
                        
                        <input type="checkbox" class="btn-check status-filter" id="inProgressFilter" checked>
                        <label class="btn btn-sm btn-outline-warning" for="inProgressFilter">В работе</label>
                        
                        <input type="checkbox" class="btn-check status-filter" id="waitingFilter" checked>
                        <label class="btn btn-sm btn-outline-secondary" for="waitingFilter">Ожидание</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <!-- Контейнер для уведомлений -->
        <div id="scheduleAlertContainer" class="p-3"></div>
        
        <!-- Таблица работ -->
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="scheduleTable">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th width="100">Статус</th>
                        <th width="100">Начало</th>
                        <th width="100">Конец</th>
                        <th width="60">Дней</th>
                        <th width="80">Действия</th>
                    </tr>
                </thead>
                <tbody id="scheduleItemsTable">
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                                <span class="visually-hidden">Загрузка...</span>
                            </div>
                            <span class="ms-2">Загрузка данных...</span>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-sm btn-success me-2" id="addScheduleRow">
                                    <i class="fas fa-plus me-1"></i> Добавить
                                </button>
                                <label for="uploadExcelBtn" class="btn btn-sm btn-outline-primary mb-0">
                                    <i class="fas fa-file-excel me-1"></i> Загрузить из Excel
                                </label>
                                <input type="file" id="uploadExcelBtn" class="d-none" accept=".xlsx, .xls">
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления/редактирования этапа работ -->
<div class="modal fade" id="scheduleItemModal" tabindex="-1" aria-labelledby="scheduleItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleItemModalLabel">Добавление этапа</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleItemForm">
                    <input type="hidden" id="scheduleItemId" name="id">
                    <input type="hidden" id="isZakupka" name="is_zakupka" value="0">
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="zakupkaCheckbox">
                            <label class="form-check-label" for="zakupkaCheckbox">Это этап закупки материалов</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Наименование этапа</label>
                        <input type="text" class="form-control" id="itemName" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="itemStatus" class="form-label">Статус</label>
                        <select class="form-select" id="itemStatus" name="status" required>
                            <option value="waiting">Ожидание</option>
                            <option value="in_progress">В работе</option>
                            <option value="completed">Завершено</option>
                        </select>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="itemStartDate" class="form-label">Дата начала</label>
                            <input type="date" class="form-control" id="itemStartDate" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="itemEndDate" class="form-label">Дата завершения</label>
                            <input type="date" class="form-control" id="itemEndDate" name="end_date" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="itemDays" class="form-label">Количество дней</label>
                        <input type="number" class="form-control" id="itemDays" name="days" min="0" readonly>
                        <small class="form-text text-muted">Рассчитывается автоматически</small>
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

<!-- Модальное окно для импорта из Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExcelModalLabel">Импорт из Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="upload-progress d-none">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-center mt-2" id="importStatus">Загрузка...</p>
                </div>
                
                <div id="importForm">
                    <p>Выберите файл Excel (.xlsx или .xls) с данными для импорта. Файл должен содержать следующие столбцы:</p>
                    <ul>
                        <li>Наименование</li>
                        <li>Статус (Готово, В работе, Ожидание)</li>
                        <li>Начало (дата в формате ДД.ММ.ГГГГ)</li>
                        <li>Конец (дата в формате ДД.ММ.ГГГГ)</li>
                        <li>Дней (число)</li>
                    </ul>
                    
                    <div class="mb-3">
                        <label for="excelFile" class="form-label">Выберите файл Excel</label>
                        <input type="file" class="form-control" id="excelFile" accept=".xlsx, .xls" required>
                        <div class="invalid-feedback">Пожалуйста, выберите файл Excel (.xlsx или .xls)</div>
                    </div>
                </div>
                
                <div id="importPreview" class="d-none">
                    <h6>Предпросмотр данных:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="previewTable">
                            <thead>
                                <tr>
                                    <th>Наименование</th>
                                    <th>Статус</th>
                                    <th>Начало</th>
                                    <th>Конец</th>
                                    <th>Дней</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Данные будут добавлены динамически -->
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-warning d-none" id="importWarnings">
                        <strong>Внимание!</strong> Обнаружены проблемы с некоторыми данными.
                        <ul id="warningsList"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary d-none" id="importPreviewBtn">Предпросмотр</button>
                <button type="button" class="btn btn-success d-none" id="importDataBtn">Импортировать</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript для управления планом-графиком работ -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация переменных
        const projectId = {{ $project->id }};
        const scheduleTable = document.getElementById('scheduleTable');
        const scheduleItemsTable = document.getElementById('scheduleItemsTable');
        const addScheduleRowBtn = document.getElementById('addScheduleRow');
        const exportScheduleBtn = document.getElementById('exportScheduleBtn');
        const uploadExcelBtn = document.getElementById('uploadExcelBtn');
        const modalElement = document.getElementById('scheduleItemModal');
        const form = document.getElementById('scheduleItemForm');
        const saveButton = document.getElementById('saveScheduleItem');
        const zakupkaCheckbox = document.getElementById('zakupkaCheckbox');
        const isZakupkaInput = document.getElementById('isZakupka');
        const startDateInput = document.getElementById('itemStartDate');
        const endDateInput = document.getElementById('itemEndDate');
        const daysInput = document.getElementById('itemDays');
        
        // Фильтры
        const startDateFilter = document.getElementById('startDateFilter');
        const endDateFilter = document.getElementById('endDateFilter');
        const applyDateFilterBtn = document.getElementById('applyDateFilter');
        const finishedFilter = document.getElementById('finishedFilter');
        const inProgressFilter = document.getElementById('inProgressFilter');
        const waitingFilter = document.getElementById('waitingFilter');
        
        let scheduleItems = [];
        let modal;
        
        // Инициализация Bootstrap Modal
        if (typeof bootstrap !== 'undefined') {
            modal = new bootstrap.Modal(modalElement);
        } else {
            console.error('Bootstrap не определен');
        }
        
        // Загрузка данных плана-графика
        loadScheduleItems();
        
        // Обработчики для фильтров
        applyDateFilterBtn.addEventListener('click', filterItems);
        document.querySelectorAll('.status-filter').forEach(checkbox => {
            checkbox.addEventListener('change', filterItems);
        });
        
        // Обработчик добавления нового этапа
        addScheduleRowBtn.addEventListener('click', function() {
            // Сброс формы
            form.reset();
            document.getElementById('scheduleItemId').value = '';
            isZakupkaInput.value = '0';
            zakupkaCheckbox.checked = false;
            
            // Установка заголовка
            document.getElementById('scheduleItemModalLabel').textContent = 'Добавление этапа';
            
            // Отображение модального окна
            modal.show();
        });
        
        // Обработчик изменения чекбокса закупки
        zakupkaCheckbox.addEventListener('change', function() {
            isZakupkaInput.value = this.checked ? '1' : '0';
            
            // Если это закупка, устанавливаем имя с префиксом
            let nameInput = document.getElementById('itemName');
            if (this.checked && nameInput.value && !nameInput.value.startsWith('(ЗАКУПКА)')) {
                nameInput.value = '(ЗАКУПКА) ' + nameInput.value;
            } else if (!this.checked && nameInput.value && nameInput.value.startsWith('(ЗАКУПКА)')) {
                nameInput.value = nameInput.value.replace('(ЗАКУПКА) ', '');
            }
            
            // Если это закупка, устанавливаем одинаковую дату начала и завершения
            if (this.checked && startDateInput.value) {
                endDateInput.value = startDateInput.value;
                calculateDays();
            }
        });
        
        // Обработчик экспорта в Excel
        exportScheduleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            exportToExcel();
        });
        
        // Обработчик загрузки из Excel
        uploadExcelBtn.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const importExcelModal = new bootstrap.Modal(document.getElementById('importExcelModal'));
                document.getElementById('importPreviewBtn').classList.remove('d-none');
                document.getElementById('excelFile').value = '';
                document.getElementById('importForm').classList.remove('d-none');
                document.getElementById('importPreview').classList.add('d-none');
                document.getElementById('importDataBtn').classList.add('d-none');
                importExcelModal.show();
            }
        });
        
        // Обработчик предпросмотра данных из Excel
        document.getElementById('importPreviewBtn').addEventListener('click', function() {
            const fileInput = document.getElementById('excelFile');
            if (fileInput.files && fileInput.files[0]) {
                previewExcelData(fileInput.files[0]);
            } else {
                fileInput.classList.add('is-invalid');
            }
        });
        
        // Обработчик импорта данных из Excel
        document.getElementById('importDataBtn').addEventListener('click', function() {
            importExcelData();
        });
        
        // Обработчики изменения дат для расчета количества дней
        startDateInput.addEventListener('change', calculateDays);
        endDateInput.addEventListener('change', calculateDays);
        
        // Функция расчета количества дней между датами
        function calculateDays() {
            if (startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                // Проверка правильности дат
                if (startDate > endDate) {
                    endDateInput.value = startDateInput.value;
                }
                
                const diffTime = Math.abs(new Date(endDateInput.value) - new Date(startDateInput.value));
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                // Устанавливаем количество дней
                daysInput.value = diffDays;
            }
        }
        
        // Обработчик сохранения этапа
        saveButton.addEventListener('click', function() {
            if (form.checkValidity()) {
                const formData = {
                    name: document.getElementById('itemName').value,
                    status: document.getElementById('itemStatus').value,
                    start_date: startDateInput.value,
                    end_date: endDateInput.value,
                    days: parseInt(daysInput.value),
                    is_zakupka: isZakupkaInput.value === '1'
                };
                
                const itemId = document.getElementById('scheduleItemId').value;
                
                if (itemId) {
                    // Обновление существующего этапа
                    updateScheduleItem(itemId, formData);
                } else {
                    // Создание нового этапа
                    createScheduleItem(formData);
                }
            } else {
                form.reportValidity();
            }
        });
        
        // Функция фильтрации элементов
        function filterItems() {
            const start = startDateFilter.value ? new Date(startDateFilter.value) : null;
            const end = endDateFilter.value ? new Date(endDateFilter.value) : null;
            const showFinished = finishedFilter.checked;
            const showInProgress = inProgressFilter.checked;
            const showWaiting = waitingFilter.checked;
            
            // Фильтруем элементы
            const filteredItems = scheduleItems.filter(item => {
                const itemStart = new Date(item.start_date);
                const itemEnd = new Date(item.end_date);
                
                // Фильтр по датам
                if (start && end) {
                    // Проверяем пересечение периодов
                    if (itemEnd < start || itemStart > end) {
                        return false;
                    }
                } else if (start && itemEnd < start) {
                    return false;
                } else if (end && itemStart > end) {
                    return false;
                }
                
                // Фильтр по статусу
                if (item.status === 'completed' && !showFinished) return false;
                if (item.status === 'in_progress' && !showInProgress) return false;
                if (item.status === 'waiting' && !showWaiting) return false;
                
                return true;
            });
            
            // Отображаем отфильтрованные элементы
            renderScheduleItems(filteredItems);
        }
        
        // Загрузка данных плана-графика
        function loadScheduleItems() {
            fetch(`/partner/projects/${projectId}/schedule/items`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при загрузке данных');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        scheduleItems = data.data;
                        renderScheduleItems(scheduleItems);
                    } else {
                        showAlert('Не удалось загрузить данные плана-графика', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка загрузки данных:', error);
                    showAlert('Произошла ошибка при загрузке данных плана-графика', 'danger');
                    
                    // Отображаем сообщение об ошибке в таблице
                    scheduleItemsTable.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-danger">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                Не удалось загрузить данные плана-графика
                            </td>
                        </tr>
                    `;
                });
        }
        
        // Отрисовка данных плана-графика
        function renderScheduleItems(items) {
            // Очистка таблицы
            scheduleItemsTable.innerHTML = '';
            
            if (items.length === 0) {
                scheduleItemsTable.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">
                            Данные отсутствуют. Нажмите кнопку "Добавить" для создания нового этапа работ.
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Сортировка по дате начала
            items.sort((a, b) => new Date(a.start_date) - new Date(b.start_date));
            
            // Добавление строк в таблицу
            items.forEach((item, index) => {
                const row = document.createElement('tr');
                
                // Статус элемента
                let statusBadge, statusClass;
                switch(item.status) {
                    case 'completed':
                        statusBadge = 'Готово';
                        statusClass = 'success';
                        break;
                    case 'in_progress':
                        statusBadge = 'В работе';
                        statusClass = 'warning';
                        break;
                    default:
                        statusBadge = 'Ожидание';
                        statusClass = 'secondary';
                }
                
                // Выделение строки закупки
                if (item.is_zakupka) {
                    row.classList.add('table-warning');
                }
                
                // Форматирование дат
                const startDate = new Date(item.start_date).toLocaleDateString();
                const endDate = new Date(item.end_date).toLocaleDateString();
                
                row.innerHTML = `
                    <td>${escapeHtml(item.name)}</td>
                    <td><span class="badge bg-${statusClass}">${statusBadge}</span></td>
                    <td>${startDate}</td>
                    <td>${endDate}</td>
                    <td>${item.days}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm edit-item" data-id="${item.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm delete-item" data-id="${item.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                scheduleItemsTable.appendChild(row);
            });
            
            // Добавляем обработчики для кнопок
            document.querySelectorAll('.edit-item').forEach(button => {
                button.addEventListener('click', function() {
                    editScheduleItem(this.getAttribute('data-id'));
                });
            });
            
            document.querySelectorAll('.delete-item').forEach(button => {
                button.addEventListener('click', function() {
                    deleteScheduleItem(this.getAttribute('data-id'));
                });
            });
        }
        
        // Функция создания нового этапа
        function createScheduleItem(data) {
            fetch(`/partner/projects/${projectId}/schedule/items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при создании этапа');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showAlert('Этап успешно добавлен', 'success');
                        loadScheduleItems();
                        modal.hide();
                    } else {
                        showAlert('Не удалось добавить этап', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка создания этапа:', error);
                    showAlert('Произошла ошибка при добавлении этапа', 'danger');
                });
        }
        
        // Функция обновления существующего этапа
        function updateScheduleItem(id, data) {
            fetch(`/partner/schedule-items/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при обновлении этапа');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showAlert('Этап успешно обновлен', 'success');
                        loadScheduleItems();
                        modal.hide();
                    } else {
                        showAlert('Не удалось обновить этап', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка обновления этапа:', error);
                    showAlert('Произошла ошибка при обновлении этапа', 'danger');
                });
        }
        
        // Функция редактирования этапа
        function editScheduleItem(id) {
            fetch(`/partner/schedule-items/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при загрузке данных этапа');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const item = data.data;
                        
                        // Заполнение формы данными
                        document.getElementById('scheduleItemId').value = item.id;
                        document.getElementById('itemName').value = item.name;
                        document.getElementById('itemStatus').value = item.status;
                        document.getElementById('itemStartDate').value = item.start_date;
                        document.getElementById('itemEndDate').value = item.end_date;
                        document.getElementById('itemDays').value = item.days;
                        document.getElementById('isZakupka').value = item.is_zakupka ? '1' : '0';
                        document.getElementById('zakupkaCheckbox').checked = item.is_zakupka;
                        
                        // Установка заголовка
                        document.getElementById('scheduleItemModalLabel').textContent = 'Редактирование этапа';
                        
                        // Отображение модального окна
                        modal.show();
                    } else {
                        showAlert('Не удалось загрузить данные этапа', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка загрузки данных этапа:', error);
                    showAlert('Произошла ошибка при загрузке данных этапа', 'danger');
                });
        }
        
        // Функция удаления этапа
        function deleteScheduleItem(id) {
            if (confirm('Вы уверены, что хотите удалить этот этап?')) {
                fetch(`/partner/schedule-items/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Ошибка при удалении этапа');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert('Этап успешно удален', 'success');
                            loadScheduleItems();
                        } else {
                            showAlert('Не удалось удалить этап', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка удаления этапа:', error);
                        showAlert('Произошла ошибка при удалении этапа', 'danger');
                    });
            }
        }
        
        // Функция экспорта в Excel
        function exportToExcel() {
            showAlert('Подготовка файла для экспорта...', 'info');
            
            fetch(`/partner/projects/${projectId}/schedule/export`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при экспорте данных');
                    }
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `plan-grafik-${projectId}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    a.remove();
                    showAlert('Файл успешно экспортирован', 'success');
                })
                .catch(error => {
                    console.error('Ошибка экспорта:', error);
                    showAlert('Произошла ошибка при экспорте данных', 'danger');
                });
        }
        
        // Функция предпросмотра данных из Excel
        function previewExcelData(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('project_id', projectId);
            
            // Показываем прогресс загрузки
            document.getElementById('importForm').classList.add('d-none');
            document.getElementById('importPreviewBtn').classList.add('d-none');
            document.getElementById('importDataBtn').classList.add('d-none');
            document.querySelector('.upload-progress').classList.remove('d-none');
            document.getElementById('importStatus').textContent = 'Анализ файла...';
            
            fetch('/partner/projects/schedule/preview-excel', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при обработке файла');
                    }
                    return response.json();
                })
                .then(data => {
                    // Скрываем прогресс загрузки
                    document.querySelector('.upload-progress').classList.add('d-none');
                    
                    if (data.success) {
                        // Показываем предпросмотр данных
                        document.getElementById('importPreview').classList.remove('d-none');
                        const previewTable = document.querySelector('#previewTable tbody');
                        previewTable.innerHTML = '';
                        
                        // Добавляем данные в таблицу предпросмотра
                        data.data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${escapeHtml(item.name)}</td>
                                <td>${item.status_text}</td>
                                <td>${item.start_date_formatted}</td>
                                <td>${item.end_date_formatted}</td>
                                <td>${item.days}</td>
                            `;
                            previewTable.appendChild(row);
                        });
                        
                        // Показываем предупреждения, если есть
                        if (data.warnings && data.warnings.length > 0) {
                            const warningsContainer = document.getElementById('importWarnings');
                            const warningsList = document.getElementById('warningsList');
                            warningsList.innerHTML = '';
                            
                            data.warnings.forEach(warning => {
                                const li = document.createElement('li');
                                li.textContent = warning;
                                warningsList.appendChild(li);
                            });
                            
                            warningsContainer.classList.remove('d-none');
                        } else {
                            document.getElementById('importWarnings').classList.add('d-none');
                        }
                        
                        // Показываем кнопку импорта
                        document.getElementById('importDataBtn').classList.remove('d-none');
                    } else {
                        showAlert('Не удалось обработать файл: ' + data.message, 'danger');
                        document.getElementById('importForm').classList.remove('d-none');
                        document.getElementById('importPreviewBtn').classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Ошибка предпросмотра Excel:', error);
                    document.querySelector('.upload-progress').classList.add('d-none');
                    document.getElementById('importForm').classList.remove('d-none');
                    document.getElementById('importPreviewBtn').classList.remove('d-none');
                    showAlert('Произошла ошибка при обработке файла Excel', 'danger');
                });
        }
        
        // Функция импорта данных из Excel
        function importExcelData() {
            const fileInput = document.getElementById('excelFile');
            if (!fileInput.files || !fileInput.files[0]) {
                showAlert('Выберите файл Excel для импорта', 'danger');
                return;
            }
            
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('project_id', projectId);
            
            // Показываем прогресс импорта
            document.getElementById('importPreview').classList.add('d-none');
            document.getElementById('importDataBtn').classList.add('d-none');
            document.querySelector('.upload-progress').classList.remove('d-none');
            document.getElementById('importStatus').textContent = 'Импорт данных...';
            
            fetch('/partner/projects/schedule/import-excel', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка при импорте данных');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('importStatus').textContent = 'Импорт успешно завершен!';
                        
                        // Закрываем модальное окно через 1.5 секунды
                        setTimeout(() => {
                            bootstrap.Modal.getInstance(document.getElementById('importExcelModal')).hide();
                            loadScheduleItems();
                            showAlert('Данные успешно импортированы из Excel', 'success');
                        }, 1500);
                    } else {
                        document.querySelector('.upload-progress').classList.add('d-none');
                        document.getElementById('importPreview').classList.remove('d-none');
                        document.getElementById('importDataBtn').classList.remove('d-none');
                        showAlert('Не удалось импортировать данные: ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Ошибка импорта Excel:', error);
                    document.querySelector('.upload-progress').classList.add('d-none');
                    document.getElementById('importPreview').classList.remove('d-none');
                    document.getElementById('importDataBtn').classList.remove('d-none');
                    showAlert('Произошла ошибка при импорте данных из Excel', 'danger');
                });
        }
        
        // Функция отображения уведомлений
        function showAlert(message, type) {
            const alertContainer = document.getElementById('scheduleAlertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alert);
            
            // Автоматическое скрытие уведомления через 5 секунд
            setTimeout(() => {
                if (alert.parentNode === alertContainer) {
                    alert.classList.remove('show');
                    setTimeout(() => {
                        if (alert.parentNode === alertContainer) {
                            alertContainer.removeChild(alert);
                        }
                    }, 300);
                }
            }, 5000);
        }
        
        // Функция экранирования HTML-символов
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