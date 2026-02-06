<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
</head>
<body>
    <header class="header">
        <div class="header__content">
            <h1 class=header__logo>
                <a href="{{ route('items.index') }}">
                    <img class="header__logo-img" src="{{ asset('images/COACHTECH-header-logo.png') }}" alt="COACHTECH">
                </a>
            </h1>
        </div>
    </header>
    <main>
        <div class="verify">
            <p class="verify__text">
                登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。
            </p>
            <a class="verify__button" href="http://localhost:8025/">認証はこちらから</a>
            <form class="verify__form" method="post" action="{{ route('verification.send') }}">
                @csrf
                <button class="verify__form-link" type="submit">認証メールを再送する</button>
            </form>

        </div>
    </main>
</body>
</html>