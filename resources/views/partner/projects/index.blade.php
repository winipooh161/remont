@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Объекты</h1>
        <a href="{{ route('partner.projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Создать объект
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Фильтры и поиск -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('partner.projects.index') }}" method="GET" id="filterForm">
                <input type="hidden" name="filter" value="true">
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" placeholder="Поиск по имени, адресу..." value="{{ $filters['search'] ?? '' }}">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <select class="form-select" name="status" id="status">
                            <option value="">Все статусы</option>
                            <option value="active" {{ isset($filters['status']) && $filters['status'] == 'active' ? 'selected' : '' }}>Активные</option>
                            <option value="completed" {{ isset($filters['status']) && $filters['status'] == 'completed' ? 'selected' : '' }}>Завершенные</option>
                            <option value="paused" {{ isset($filters['status']) && $filters['status'] == 'paused' ? 'selected' : '' }}>Приостановленные</option>
                            <option value="cancelled" {{ isset($filters['status']) && $filters['status'] == 'cancelled' ? 'selected' : '' }}>Отмененные</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <select class="form-select" name="work_type" id="work_type">
                            <option value="">Все типы работ</option>
                            <option value="repair" {{ isset($filters['work_type']) && $filters['work_type'] == 'repair' ? 'selected' : '' }}>Ремонт</option>
                            <option value="design" {{ isset($filters['work_type']) && $filters['work_type'] == 'design' ? 'selected' : '' }}>Дизайн</option>
                            <option value="construction" {{ isset($filters['work_type']) && $filters['work_type'] == 'construction' ? 'selected' : '' }}>Строительство</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <select class="form-select" name="branch" id="branch">
                            <option value="">Все филиалы</option>
                            <option value="Москва" {{ isset($filters['branch']) && $filters['branch'] == 'Москва' ? 'selected' : '' }}>Москва</option>
                            <option value="Санкт-Петербург" {{ isset($filters['branch']) && $filters['branch'] == 'Санкт-Петербург' ? 'selected' : '' }}>Санкт-Петербург</option>
                            <option value="Казань" {{ isset($filters['branch']) && $filters['branch'] == 'Казань' ? 'selected' : '' }}>Казань</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary me-2">Применить</button>
                        <a href="{{ route('partner.projects.index', ['clear' => true]) }}" class="btn btn-outline-secondary">Сбросить</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Информация о примененных фильтрах -->
    @if(!empty(array_filter($filters ?? [])))
        <div class="mb-3">
            <div class="d-flex align-items-center flex-wrap">
                <span class="me-2">Применены фильтры:</span>
                @if(!empty($filters['search']))
                    <span class="badge bg-light text-dark me-2 mb-2">Поиск: {{ $filters['search'] }}</span>
                @endif
                @if(!empty($filters['status']))
                    <span class="badge bg-light text-dark me-2 mb-2">Статус: 
                        {{ $filters['status'] == 'active' ? 'Активные' : 
                          ($filters['status'] == 'completed' ? 'Завершенные' : 
                          ($filters['status'] == 'paused' ? 'Приостановленные' : 'Отмененные')) }}
                    </span>
                @endif
                @if(!empty($filters['work_type']))
                    <span class="badge bg-light text-dark me-2 mb-2">Тип работ: 
                        {{ $filters['work_type'] == 'repair' ? 'Ремонт' : 
                          ($filters['work_type'] == 'design' ? 'Дизайн' : 'Строительство') }}
                    </span>
                @endif
                @if(!empty($filters['branch']))
                    <span class="badge bg-light text-dark me-2 mb-2">Филиал: {{ $filters['branch'] }}</span>
                @endif
            </div>
        </div>
    @endif
    
    @if($projects->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <h4 class="text-muted mb-3">Объекты не найдены</h4>
                @if(!empty(array_filter($filters ?? [])))
                    <p>Попробуйте изменить параметры фильтрации или <a href="{{ route('partner.projects.index', ['clear' => true]) }}">сбросить все фильтры</a>.</p>
                @else
                    <p>Создайте свой первый объект, нажав на кнопку "Создать объект" выше.</p>
                    <a href="{{ route('partner.projects.create') }}" class="btn btn-outline-primary mt-3">
                        <i class="fas fa-plus-circle me-2"></i>Создать объект
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="row">
            @foreach($projects as $project)
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card h-100 project-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <a href="{{ route('partner.projects.show', $project) }}" class="text-decoration-none text-dark">
                                    {{ $project->client_name }}
                                </a>
                            </h5>
                            <span class="badge {{ $project->status == 'active' ? 'bg-success' : ($project->status == 'paused' ? 'bg-warning' : ($project->status == 'completed' ? 'bg-info' : 'bg-secondary')) }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                {{ $project->address }}
                                @if($project->apartment_number)
                                    , кв. {{ $project->apartment_number }}
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-phone text-muted me-2"></i>{{ $project->phone }}
                                    </div>
                                    <div>
                                        <i class="fas fa-ruler-combined text-muted me-2"></i>{{ $project->area ?? '-' }} м²
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-tools text-muted me-2"></i>{{ $project->work_type_text }}
                                    </div>
                                    <div>
                                        <i class="fas fa-home text-muted me-2"></i>{{ $project->object_type ?? 'Не указан' }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($project->contract_date)
                            <div class="mb-2">
                                <i class="fas fa-file-signature text-muted me-2"></i>Договор: 
                                {{ $project->contract_date->format('d.m.Y') }}, 
                                №{{ $project->contract_number ?? '-' }}
                            </div>
                            @endif
                            
                            @if($project->work_start_date)
                            <div class="mb-2">
                                <i class="fas fa-calendar-alt text-muted me-2"></i>Начало работ: 
                                {{ $project->work_start_date->format('d.m.Y') }}
                            </div>
                            @endif
                            
                            <div class="mb-2">
                                <i class="fas fa-money-bill-wave text-muted me-2"></i>Сумма: 
                                {{ number_format($project->total_amount, 0, '.', ' ') }} ₽
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('partner.projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Просмотр
                            </a>
                            <a href="{{ route('partner.projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i>Редактировать
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $projects->links() }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Получаем элементы формы
        const filterForm = document.getElementById('filterForm');
        const filterSelects = filterForm.querySelectorAll('select');
        const searchInput = filterForm.querySelector('input[name="search"]');
        
        // Авто-отправка формы при изменении селектов
        filterSelects.forEach(function(select) {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
        
        // Отправка формы поиска после паузы в наборе текста
        let typingTimer;
        const doneTypingInterval = 800; // время в мс
        
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            if (searchInput.value) {
                typingTimer = setTimeout(function() {
                    filterForm.submit();
                }, doneTypingInterval);
            }
        });
        
        // Сбросить таймер, если пользователь продолжил печатать
        searchInput.addEventListener('keydown', function() {
            clearTimeout(typingTimer);
        });
    });
</script>
@endsection
