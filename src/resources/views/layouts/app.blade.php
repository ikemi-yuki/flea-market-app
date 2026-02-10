<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__container">
            <div class="header__main">
                <h1 class=header__logo>
                    <a href="{{ route('items.index') }}">
                        <img class="header__logo-img" src="{{ asset('images/COACHTECH-header-logo.png') }}" alt="COACHTECH">
                    </a>
                </h1>
                <form class="search-form" action="{{ route('items.index') }}" method="get">
                    @csrf
                    <input class="search-form__item" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ $keyword }}">
                </form>
            </div>
            <div class="header-nav">
                <nav class="nav">
                    <ul class="nav__list">
                        @auth
                            <li class="nav__item">
                                <form class="nav__item-form" action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <input class="nav__link--logout" type="submit" value="ログアウト">
                                </form>
                            </li>
                        @endauth
                        @guest
                            <li class="nav__item">
                                <a class="nav__link--login" href="{{ route('login') }}">ログイン</a>
                            </li>
                        @endguest
                        <li class="nav__item">
                            <a class="nav__link" href="{{ route('mypage.index') }}">マイページ</a>
                        </li>
                        <li class="nav__item--button">
                            <a class="nav__link--sell" href="{{ route('sell.index') }}">出品</a>
                        </li>
                    </ul>
                    <a class="fab-sell" href="{{ route('sell.index') }}">出品</a>
                </nav>
            </div>
        </div>
    </header>
    <main>
        @yield('content')

        @stack('scripts')
    </main>
</body>
</html>