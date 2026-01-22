@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    <div class="purchase">
        <div class="purchase-info">
            <div class="purchase-item">
                <div class="purchase-item__image-container">
                    <img class="purchase-item__image" src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                </div>
                <div class="purchase-item__content">
                    <h2 class="purchase-item__name">{{ $item->name }}</h2>
                    <p class="purchase-item__price"><span class="purchase-item__price--yen">￥</span>{{ number_format($item->price) }}</p>
                </div>
            </div>
            <div class="purchase-payment">
                <h3 class="purchase-payment__title">支払方法</h3>
                <details class="payment">
                    <summary class="payment__select" for="payment-open">
                        {{ session('payment_method') == 1 ? 'コンビニ払い' : '' }}
                        {{ session('payment_method') == 2 ? 'カード支払い' : '' }}
                        {{ !session('payment_method') ? '選択してください' : '' }}</summary>
                    <div class="payment__select-list">
                        <form class="payment-form" action="{{ route('purchase.updatePayment', ['item_id' => $item->id]) }}" method="post">
                            @csrf
                            <div class="payment-form__button">
                                <input type="hidden" name="payment_method" value="1">
                                <button class="payment-form__button-submit  {{ session('payment_method') == 1 ? 'is-active' : '' }} " type="submit">コンビニ払い</button>
                            </div>
                        </form>
                        <form class="payment-form" action="{{ route('purchase.updatePayment', ['item_id' => $item->id]) }}" method="post">
                            @csrf
                            <div class="payment-form__button">
                                <input type="hidden" name="payment_method" value="2">
                                <button class="payment-form__button-submit {{ session('payment_method') == 2 ? 'is-active' : '' }}" type="submit">カード支払い</button>
                            </div>
                        </form>
                    </div>
                </details>
            </div>
            <div class="purchase-address">
                <div class="purchase-address__wrapper">
                    <h3 class="purchase-address__title">配送先</h3>
                    <a class="purchase-address__change-link" href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}">変更する</a>
                </div>
                <div class="purchase-address__item">
                    <p class="purchase-address__item-post">〒 {{ $address['post_code'] }}</p>
                    <p class="purchase-address__item-address">
                        {{ $address['address'] }}
                            @if (!empty($address['building']))
                                {{ $address['building'] }}
                            @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="purchase-subtotal">
            <form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="post">
                @csrf
                <div class="subtotal-table">
                    <table class="subtotal-table__inner">
                        <tr class="subtotal-table__row">
                            <th class="subtotal-table__header">商品代金</th>
                            <td class="subtotal-table__price"><span class="subtotal-table__price--yen">￥</span>{{ number_format($item->price) }}</td>
                        </tr>
                        <tr class="subtotal-table__row">
                            <th class="subtotal-table__header">支払方法</th>
                            <td class="subtotal-table__payment">{{ session('payment_method', 1) == 1 ? 'コンビニ払い' : 'カード支払い' }}</td>
                        </tr>
                    </table>
                </div>
                <input type="hidden" name="payment_method" value="{{ session('payment_method') }}">
                <input type="hidden" name="post_code" value="{{ $address['post_code'] }}">
                <input type="hidden" name="address" value="{{ $address['address'] }}">
                <input type="hidden" name="building" value="{{ $address['building'] ?? '' }}">
                <div class="form__button">
                    <button class="form__button-submit" type="submit">購入する</button>
                </div>
            </form>
        </div>
    </div>
@endsection