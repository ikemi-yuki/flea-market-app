<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <header class="header">
        <div class="header__content">
            <h1 class=header__logo>
                <img class="header__logo-img" src="{{ asset('images/COACHTECH-header-logo.png') }}" alt="COACHTECH">
            </h1>
        </div>
    </header>
    <main>
    <div class="login-form__content">
        <div class="login-form__header">
            <h2 class="login-form__header-title">ログイン</h2>
        </div>
        <form class="form" action="{{ route('login') }}" method="post">
            @csrf
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">メールアドレス</span>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="email" name="email" value="{{ old('email') }}">
                    <div class="form__error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">パスワード</span>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="password" name="password">
                    <div class="form__error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">ログインする</button>
            </div>
        </form>
        <div class="register__link">
            <a class="register__button-submit" href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </div>
