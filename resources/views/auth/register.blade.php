@extends('layouts.auth')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Левая колонка с формой -->
        <div class="col-md-6 bg-white">
            <div class="register-form-container d-flex align-items-center py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-xl-8 mx-auto">
                            <h3 class="display-6 mb-4">{{ __('Регистрация') }}</h3>
                            <div class="card border-0 shadow rounded-3">
                                <div class="card-body p-4 p-sm-5">
                                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="name" class="form-label">{{ __('Имя') }} <small class="text-muted">(только русские буквы)</small></label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">{{ __('Номер телефона') }}</label>
                                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                                name="phone" value="{{ old('phone') }}" required autocomplete="phone"
                                                placeholder="+7 (___) ___-__-__">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('Email адрес') }} ({{ __('Необязательно') }})</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                                name="email" value="{{ old('email') }}" autocomplete="email"
                                                placeholder="example@domain.com">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">{{ __('Пароль') }}</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                                name="password" required autocomplete="new-password"
                                                placeholder="Введите пароль">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password-confirm" class="form-label">{{ __('Подтверждение пароля') }}</label>
                                            <input id="password-confirm" type="password" class="form-control" 
                                                name="password_confirmation" required autocomplete="new-password"
                                                placeholder="Повторите пароль">
                                        </div>

                                        <div class="d-grid gap-2 mb-3">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Зарегистрироваться') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Правая колонка с фоновым изображением -->
        <div class="col-md-6 d-none d-md-block p-0">
            <div class="bg-image h-100" style="background-image: url('https://images.unsplash.com/photo-1556912173-3bb406ef7e77?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1287&q=80'); background-size: cover; background-position: center;"></div>
        </div>
    </div>
</div>
<style>
.register-form-container {
    min-height: calc(100vh - 72px);
}
.bg-image {
    min-height: calc(100vh - 72px);
}
</style>
@endsection
