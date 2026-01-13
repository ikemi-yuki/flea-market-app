<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__content">
            <h1 class=header__logo>
                <img class="header__logo-img" src="{{ asset('images/COACHTECH-header-logo.png') }}" alt="COACHTECH">
            </h1>
            <form class="search-form" action="{{ route('items.index') }}" method="get">
                @csrf
                <input class="search-form__item" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ $keyword }}">
            </form>
            <div class="header-nav">
                @auth
                    <form class="header-nav__form" action="{{ route('logout') }}" method="post">
                        @csrf
                        <input class="header__link--logout" type="submit" value="ログアウト">
                    </form>
                @endauth
                @guest
                    <a class="header__link" href="{{ route('login') }}">ログイン</a>
                @endguest
                <a class="header__link" href="{{ route('mypage.index') }}">マイページ</a>
                <a class="header__link--sell" href="/sell">出品</a>
            </div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>