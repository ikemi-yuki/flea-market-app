@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <div class="mypage">
        <div class="mypage-user">
            @if ($user->profile->icon_path)
                <img class="mypage-user__img" src="{{ $user->profile->icon_url }}" alt="プロフィール画像">
            @else
                <div class="profile__image-default"></div>
            @endif
            <p class="mypage-user__name">{{ $user->profile->name }}</p>
        </div>
        <div class="profile-edit">
            <a class="profile-edit__button" href="{{ route('profile.edit') }}">プロフィールを編集</a>
        </div>
    </div>
    <div class="tabs">
        <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="mypage__tab{{ $page === 'sell' ? '--active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="mypage__tab{{ $page === 'buy' ? '--active' : '' }}">
            購入した商品
        </a>
    </div>
    <div class="items">
        @foreach ($items as $item)
            <div class="item-card">
                <img class="item-card__img" src="{{ $item->image_url }}" alt="{{ $item->name }}">
                <div class="item-card__info">
                    <p class="item-card__name">
                        {{ $item->name }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endsection