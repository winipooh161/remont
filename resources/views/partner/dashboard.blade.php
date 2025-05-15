@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Панель управления партнера') }}</div>

                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        Вы вошли как партнер!
                    </div>
                    
                    <div class="list-group mt-4">
                        <a href="#" class="list-group-item list-group-item-action">
                            Мои заказы
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            Профиль партнера
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            Доступные услуги
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
