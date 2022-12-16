<header class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a href="/"><img src="/image/logo.svg" alt=""></a>
        </div>
        <ul class="navbar-menu form-inline">
            <li><a href="{{ route('question.index')}}">Вопросы</a></li>
            <li><a href="{{ route('tag.index')}}">Тэги</a></li>
            <li><a href="{{ route('user.index')}}">Пользователи</a></li>
            @can('register-user')
            <li><a href="{{ route('register') }}">Добавить пользователя</a></li>
            @endcan
        </ul>
        <ul class="navbar-menu form-inline">
            <!-- @guest
            @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Войти') }}</a>
            </li>
            @endif
            @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Зарегистрироваться') }}</a>
            </li>
            @endif
            @else -->
            <li class="nav-item">
                <a id="navbarDropdown" href="{{route('profile.index')}}" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ auth()->user()->name }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Выйти') }}
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            </li>
            <!-- @endguest -->
        </ul>
    </div>
</header>