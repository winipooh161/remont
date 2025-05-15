@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Редактирование объекта</h5>
            <div>
                <a href="{{ route('partner.projects.show', $project) }}" class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-eye me-1"></i>Просмотр
                </a>
                <a href="{{ route('partner.projects.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Назад к списку
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('partner.projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Основная информация -->
                    <div class="col-md-6">
                        <h6 class="mb-3">Основная информация</h6>
                        
                        <div class="mb-3">
                            <label for="client_name" class="form-label">Имя и фамилия клиента *</label>
                            <input type="text" class="form-control @error('client_name') is-invalid @enderror" id="client_name" name="client_name" value="{{ old('client_name', $project->client_name) }}" required>
                            @error('client_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Адрес объекта *</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $project->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="apartment_number" class="form-label">Номер квартиры</label>
                            <input type="text" class="form-control @error('apartment_number') is-invalid @enderror" id="apartment_number" name="apartment_number" value="{{ old('apartment_number', $project->apartment_number) }}">
                            @error('apartment_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="area" class="form-label">Площадь объекта (м²)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('area') is-invalid @enderror" id="area" name="area" value="{{ old('area', $project->area) }}">
                            @error('area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон клиента *</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $project->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="object_type" class="form-label">Тип объекта</label>
                            <select class="form-select @error('object_type') is-invalid @enderror" id="object_type" name="object_type">
                                <option value="" {{ old('object_type', $project->object_type) == '' ? 'selected' : '' }}>Выберите тип объекта</option>
                                <option value="Квартира" {{ old('object_type', $project->object_type) == 'Квартира' ? 'selected' : '' }}>Квартира</option>
                                <option value="Дом" {{ old('object_type', $project->object_type) == 'Дом' ? 'selected' : '' }}>Дом</option>
                                <option value="Офис" {{ old('object_type', $project->object_type) == 'Офис' ? 'selected' : '' }}>Офис</option>
                                <option value="Коммерческое помещение" {{ old('object_type', $project->object_type) == 'Коммерческое помещение' ? 'selected' : '' }}>Коммерческое помещение</option>
                            </select>
                            @error('object_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Тип работ *</label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="work_type" id="work_type_repair" value="repair" {{ old('work_type', $project->work_type) == 'repair' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="work_type_repair">Ремонт</label>
                                </div>
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="work_type" id="work_type_design" value="design" {{ old('work_type', $project->work_type) == 'design' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="work_type_design">Дизайн</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="work_type_construction" value="construction" {{ old('work_type', $project->work_type) == 'construction' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="work_type_construction">Строительство</label>
                                </div>
                            </div>
                            @error('work_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Статус проекта</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Активный</option>
                                <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Завершён</option>
                                <option value="paused" {{ old('status', $project->status) == 'paused' ? 'selected' : '' }}>Приостановлен</option>
                                <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Отменён</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Информация о договоре -->
                    <div class="col-md-6">
                        <h6 class="mb-3">Информация о договоре</h6>
                        
                        <div class="mb-3">
                            <label for="contract_date" class="form-label">Дата договора</label>
                            <input type="date" class="form-control @error('contract_date') is-invalid @enderror" id="contract_date" name="contract_date" value="{{ old('contract_date', $project->contract_date ? $project->contract_date->format('Y-m-d') : '') }}">
                            @error('contract_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="contract_number" class="form-label">Номер договора</label>
                            <input type="text" class="form-control @error('contract_number') is-invalid @enderror" id="contract_number" name="contract_number" value="{{ old('contract_number', $project->contract_number) }}">
                            @error('contract_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="work_start_date" class="form-label">Дата начала работ</label>
                            <input type="date" class="form-control @error('work_start_date') is-invalid @enderror" id="work_start_date" name="work_start_date" value="{{ old('work_start_date', $project->work_start_date ? $project->work_start_date->format('Y-m-d') : '') }}">
                            @error('work_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="work_amount" class="form-label">Сумма на работы по договору (₽)</label>
                            <input type="number" min="0" step="0.01" class="form-control @error('work_amount') is-invalid @enderror" id="work_amount" name="work_amount" value="{{ old('work_amount', $project->work_amount) }}">
                            @error('work_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="materials_amount" class="form-label">Сумма на материалы по договору (₽)</label>
                            <input type="number" min="0" step="0.01" class="form-control @error('materials_amount') is-invalid @enderror" id="materials_amount" name="materials_amount" value="{{ old('materials_amount', $project->materials_amount) }}">
                            @error('materials_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Общая сумма (₽)</label>
                            <input type="text" class="form-control" id="total_amount" readonly>
                        </div>
                    </div>
                    
                    <!-- Дополнительная информация -->
                    <div class="col-md-12 mt-3">
                        <h6 class="mb-3">Дополнительная информация</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="camera_link" class="form-label">Ссылка на IP камеру</label>
                                    <input type="url" class="form-control @error('camera_link') is-invalid @enderror" id="camera_link" name="camera_link" value="{{ old('camera_link', $project->camera_link) }}" placeholder="https://">
                                    @error('camera_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="schedule_link" class="form-label">Ссылка на линейный график</label>
                                    <input type="url" class="form-control @error('schedule_link') is-invalid @enderror" id="schedule_link" name="schedule_link" value="{{ old('schedule_link', $project->schedule_link) }}" placeholder="https://">
                                    @error('schedule_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="code_inserted" name="code_inserted" value="1" {{ old('code_inserted', $project->code_inserted) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="code_inserted">Вставлен код</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phones" class="form-label">Телефоны для связи</label>
                                    <textarea class="form-control @error('contact_phones') is-invalid @enderror" id="contact_phones" name="contact_phones" rows="3" placeholder="Укажите дополнительные контактные телефоны, по одному на строку">{{ old('contact_phones', $project->contact_phones) }}</textarea>
                                    @error('contact_phones')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="branch" class="form-label">Филиал</label>
                                    <select class="form-select @error('branch') is-invalid @enderror" id="branch" name="branch">
                                        <option value="" {{ old('branch', $project->branch) == '' ? 'selected' : '' }}>Выберите филиал</option>
                                        <option value="Москва" {{ old('branch', $project->branch) == 'Москва' ? 'selected' : '' }}>Москва</option>
                                        <option value="Санкт-Петербург" {{ old('branch', $project->branch) == 'Санкт-Петербург' ? 'selected' : '' }}>Санкт-Петербург</option>
                                        <option value="Казань" {{ old('branch', $project->branch) == 'Казань' ? 'selected' : '' }}>Казань</option>
                                    </select>
                                    @error('branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('partner.projects.show', $project) }}" class="btn btn-secondary me-2">Отмена</a>
                            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Калькулятор общей суммы
    document.addEventListener('DOMContentLoaded', function() {
        const workAmount = document.getElementById('work_amount');
        const materialsAmount = document.getElementById('materials_amount');
        const totalAmount = document.getElementById('total_amount');
        
        const calculateTotal = function() {
            const work = parseFloat(workAmount.value) || 0;
            const materials = parseFloat(materialsAmount.value) || 0;
            const total = work + materials;
            totalAmount.value = total.toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ₽';
        };
        
        workAmount.addEventListener('input', calculateTotal);
        materialsAmount.addEventListener('input', calculateTotal);
        
        // Инициализация
        calculateTotal();
    });
</script>
@endsection
