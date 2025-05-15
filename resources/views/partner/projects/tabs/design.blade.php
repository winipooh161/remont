<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Дизайн-проект</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDesignModal">
            <i class="fas fa-upload me-1"></i>Загрузить файлы
        </button>
    </div>

    @if($project->designFiles->isEmpty())
        <div class="alert alert-info">
            В этом разделе будет отображаться информация о дизайн-проекте. 
            Нажмите на кнопку "Загрузить файлы", чтобы добавить файлы дизайн-проекта.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 files-container">
            @foreach($project->designFiles as $file)
                <div class="col file-item" data-file-id="{{ $file->id }}">
                    <div class="card h-100">
                        @if($file->is_image)
                            <div class="card-img-top file-preview" style="height: 140px; background-image: url('{{ asset('storage/project_files/' . $project->id . '/' . $file->filename) }}'); background-size: cover; background-position: center;"></div>
                        @else
                            <div class="card-img-top text-center bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                                <i class="{{ $file->file_icon }} fa-3x text-secondary"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title text-truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</h6>
                            <p class="card-text text-muted small mb-2">
                                <span>{{ number_format($file->size / 1024, 2) }} KB</span>
                                <span class="ms-2">{{ $file->created_at->format('d.m.Y H:i') }}</span>
                            </p>
                            <p class="card-text small text-muted">
                                {{ $file->description }}
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ $file->download_url }}" class="btn btn-sm btn-outline-primary" download>
                                <i class="fas fa-download me-1"></i>Скачать
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-file" data-file-id="{{ $file->id }}">
                                <i class="fas fa-trash me-1"></i>Удалить
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Модальное окно загрузки файла дизайн-проекта -->
<div class="modal fade" id="uploadDesignModal" tabindex="-1" aria-labelledby="uploadDesignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDesignModalLabel">Загрузить файлы дизайн-проекта</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadDesignForm" method="POST" enctype="multipart/form-data" action="{{ route('partner.project-files.store', $project) }}">
                    @csrf
                    <input type="hidden" name="file_type" value="design">
                    
                    <div class="mb-3">
                        <label for="designFile" class="form-label">Выберите файлы для загрузки</label>
                        <input class="form-control" type="file" id="designFile" name="file" required>
                        <div class="form-text">Максимальный размер файла: 10 МБ</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="designDescription" class="form-label">Описание файла (необязательно)</label>
                        <textarea class="form-control" id="designDescription" name="description" rows="2" placeholder="Добавьте краткое описание файла"></textarea>
                    </div>
                </form>
                
                <div class="upload-progress d-none">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                    <p class="text-center mt-2 mb-0 progress-info">Загрузка файла...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="uploadDesignButton">Загрузить</button>
            </div>
        </div>
    </div>
</div>
