<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>{{ config('app.name', 'Laravel') }}</h3>
        <button type="button" id="sidebarCollapse" class="btn btn-light d-block d-md-none">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sidebar-profile py-3 px-4 mb-3">
        @auth
            <div class="d-flex align-items-center">
                <div class="profile-icon">
                    <img src="{{ Auth::user()->getAvatarUrl() }}" alt="{{ Auth::user()->name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                </div>
                <div class="ms-3">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <div><span class="badge bg-secondary">{{ ucfirst(Auth::user()->role) }}</span></div>
                </div>
            </div>
        @endguest
    </div>

    <ul class="list-unstyled components">
        <li class="{{ Request::is('home') ? 'active' : '' }}">
            <a href="{{ url('/home') }}"><i class="fas fa-home me-2"></i>Главная</a>
        </li>

        @auth
            <li class="{{ Request::is('profile') ? 'active' : '' }}">
                <a href="{{ route('profile.index') }}"><i class="fas fa-user me-2"></i>Мой профиль</a>
            </li>

            @if(Auth::user()->isAdmin())
            <!-- Меню администратора -->
            <li class="sidebar-header mt-3 mb-2">Администрирование</li>
            <li class="{{ Request::is('admin') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Панель управления</a>
            </li>
            <li>
                <a href="#userSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fas fa-users me-2"></i>Пользователи
                </a>
                <ul class="collapse list-unstyled" id="userSubmenu">
                    <li>
                        <a href="#"><i class="fas fa-list me-2"></i>Все пользователи</a>
                    </li>
                </ul>
            </li>
           
           
            @endif

            @if(Auth::user()->isPartner() || Auth::user()->isAdmin())
            <!-- Меню партнера -->
            <li class="sidebar-header mt-3 mb-2">Партнёрский раздел</li>
            <li class="{{ Request::is('partner') ? 'active' : '' }}">
                <a href="{{ route('partner.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Панель партнёра</a>
            </li>
            <li class="{{ Request::is('partner/projects*') ? 'active' : '' }}">
                <a href="{{ route('partner.projects.index') }}"><i class="fas fa-building me-2"></i>Объекты</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-clipboard-list me-2"></i>Мои заказы</a>
            </li>
           
            @endif

            @if(Auth::user()->isClient() || true)
            <!-- Меню клиента -->
            <li class="sidebar-header mt-3 mb-2">Клиентский раздел</li>
            <li>
                <a href="#"><i class="fas fa-file-invoice me-2"></i>Мои заказы</a>
            </li>
           
            @endif
        @endauth

        <!-- Общие пункты меню -->
        <li class="sidebar-header mt-3 mb-2">Информация</li>
        <li>
            <a href="#"><i class="fas fa-info-circle me-2"></i>О компании</a>
        </li>
        <li>
            <a href="#"><i class="fas fa-phone me-2"></i>Контакты</a>
        </li>
        <li>
            <a href="#"><i class="fas fa-question-circle me-2"></i>Помощь</a>
        </li>

        @auth
        <li class="mt-3">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i>{{ __('Выйти') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
        @else
        <li>
            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i>{{ __('Войти') }}</a>
        </li>
        <li>
            <a href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i>{{ __('Регистрация') }}</a>
        </li>
        @endauth
    </ul>
</nav>
