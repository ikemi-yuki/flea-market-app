@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('link')
    <form class="search-form" action="{{ route('items.index') }}" method="get">
        @csrf
        <input class="search-form__item" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ $keyword }}">
    </form>
    <div class="header-nav">
    @auth
        <form class="header-nav__form" action="{{ route('logout') }}" method="post">
            @csrf
            <input class="header__link" type="submit" value="ログアウト">
        </form>
    @endauth
    @guest
        <a class="header__link--login" href="{{ route('login') }}">ログイン</a>
    @endguest
    <form class="header-nav__form" action="/mypage" method="get">
        @csrf
        <input class="header__link" type="submit" value="マイページ">
    </form>
    <form class="header-nav__form" action="/sell" method="get">
        @csrf
        <input class="header__link--sell" type="submit" value="出品">
    </form>
    </div>
@endsection

@section('content')
    <div class="tabs">
        <a href="{{ route('items.index', ['keyword' => $keyword]) }}" class="item__tab{{ $tab === 'all' ? '--active' : '' }}">
            おすすめ
        </a>
        <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}" class="item__tab{{ $tab === 'mylist' ? '--active' : '' }}">
            マイリスト
        </a>
    </div>
    @if ($tab === 'mylist' && !Auth::check())
        {{-- 何も表示しない --}}
    @else
        <div class="items">
            @foreach ($items as $item)
                <div class="item-card">
                    <img class="item-card__img" src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                    <p class="item-card__name">
                        {{ $item['name'] }}
                    </p>
                    @if ($item->status === 2)
                        <span class="item-card__status">Sold</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@endsection