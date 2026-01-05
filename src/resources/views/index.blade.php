@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('link')
    <form class="search-form" action="" method="get">
        @csrf
        <input class="search-form__item" type="text" name="keyword" placeholder="なにをお探しですか？">
    </form>
    <form class="" action="{{ route('logout') }}" method="post">
        @csrf
        <input class="header__link" type="submit" value="ログアウト">
    </form>
    <form class="" action="/mypage" method="get">
        @csrf
        <input class="header__link" type="submit" value="マイページ">
    </form>
    <form class="" action="/sell" method="get">
        @csrf
        <input class="header__link--sell" type="submit" value="出品">
    </form>
@endsection

@section('content')
    <div class="tabs">
        <a href="/" class="item__tab--active">
            おすすめ
        </a>
        <a href="/" class="item__tab">
            マイリスト
        </a>
    </div>
        <div class="items">
                <div class="item-card">
                    <img class="item-card__img" src="" alt="">
                    <p class="item-card__name">
                        商品名
                    </p>
                </div>
                <div class="item-card">
                    <img class="item-card__img" src="" alt="">
                    <p class="item-card__name">
                        商品名
                    </p>
                </div>
                <div class="item-card">
                    <img class="item-card__img" src="" alt="">
                    <p class="item-card__name">
                        商品名
                    </p>
                </div>
                <div class="item-card">
                    <img class="item-card__img" src="" alt="">
                    <p class="item-card__name">
                        商品名
                    </p>
                </div>
                <div class="item-card">
                    <img class="item-card__img" src="" alt="">
                    <p class="item-card__name">
                        商品名
                    </p>
                </div>
                <div class="item-card">
                    <img class="item-card__img" src="" alt="">
                    <p class="item-card__name">
                        商品名
                    </p>
                </div>
                <div class="item-card">
                    <img class="item-card__img" src="" alt="">
                    <p class="item-card__name">
                        商品名
                    </p>
                </div>
        </div>
@endsection