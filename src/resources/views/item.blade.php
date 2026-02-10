@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
    <div class="item-detail">
        <div class="item-detail__image-wrapper">
            <img class="item-detail__image" src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
        </div>
        <div class="item-detail__content">
            <h2 class="item-detail__name">
                {{ $item->name }}
            </h2>
            <p class="item-detail__brand">
                {{ $item->brand }}
            </p>
            <p class="item-detail__price">
                <span class="item-detail__price--yen">¥</span>
                {{ number_format($item->price) }}
                <span class="item-detail__price--tax">(税込)</span>
            </p>
            <div class="item-detail__reactions">
                <div class="item-detail__like">
                    @auth
                        <form class="like-form" action="{{ route('items.like', ['item_id' => $item->id]) }}" method="post">
                            @csrf
                            <button class="like-button" type="submit">
                                <img class="like-icon" src="{{ asset($isLiked ? 'images/heart-logo_pink.png' : 'images/heart-logo_default.png') }}" alt="いいね">
                            </button>
                        </form>
                    @else
                        <img class="like-icon" src="{{ asset('images/heart-logo_default.png') }}" alt="いいね">
                    @endauth
                    <span class="like-count">{{ $likeCount }}</span>
                </div>
                <div class="item-detail__comment">
                    <img class="comment-icon" src="{{ asset('images/speech-bubble-logo.png') }}" alt="コメント">
                    <span class="comment-count">{{ $commentCount }}</span>
                </div>
            </div>
            <div class="item-purchase">
                <a class="item-purchase__button" href="{{ route('purchase.show', ['item_id' => $item->id]) }}">
                    購入手続きへ
                </a>
            </div>
            <h3 class="item-detail__description">商品説明</h3>
            <p class="item-detail__description-text">
                {{ $item->description }}
            </p>
            <h3 class="item-detail__info">商品の情報</h3>
            <div class="info-table">
                <table class="info-table__inner">
                    <tr class="info-table__row">
                        <th class="info-table__header">カテゴリー</th>
                        <td class="info-table__item">
                            @foreach ($item->categories as $category)
                                <p class="info-table__item-category">{{ $category->name }}</p>
                            @endforeach
                        </td>
                    </tr>
                    <tr class="info-table__row">
                        <th class="info-table__header">商品の状態</th>
                        <td class="info-table__item">
                            <p class="info-table__item-condition">{{ $conditionText[$item->condition] }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <h3 class="comment">コメント({{ $commentCount }})</h3>
            @foreach ($item->comments as $comment)
                <div class="comment-user">
                    @if ($comment->user->profile->icon_path)
                        <img class="comment-user__img" src="{{ asset('storage/' . ($comment->user->profile->icon_path)) }}" alt="プロフィール画像">
                    @else
                        <div class="profile__image-default"></div>
                    @endif
                    <p class="comment-user__name">{{ $comment->user->profile->name }}</p>
                </div>
                <div class="comment-content">
                    <p class="comment-content__text">{{ $comment->content }}</p>
                </div>
            @endforeach
            <p class="comment-form__title">商品へのコメント</p>
            <form class="comment-form" action="{{ route('items.comment', ['item_id' => $item->id]) }}" method="post">
                @csrf
                <textarea class="comment-form__text" name="content">{{ old('content') }}</textarea>
                <div class="comment-form__error">
                    @error('content')
                        {{ $message }}
                    @enderror
                </div>
                <div class="comment-form__button">
                    <button class="comment-form__button-submit" type="submit">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
@endsection