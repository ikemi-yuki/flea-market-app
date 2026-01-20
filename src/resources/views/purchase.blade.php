@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    <div class="purchase">
        <div class="purchase-info">
            <div class="purchase-item">
                <div class="purchase-item__image-wrapper">
                    <img class="purchase-item__image" src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                </div>
                <div class="purchase-item__content">
                    <h2 class="purchase-item__name">{{ $item->name }}</h2>
                    <p class="purchase-item__price"><span class="purchase-item__price--yen">￥</span>{{ number_format($item->price) }}</p>
                </div>
            </div>
            <div class="purchase-payment">
                <h3 class="purchase-payment__title">支払方法</h3>
                <form class="payment-form" action="{{ route('purchase.updatePayment', ['item_id' => $item->id]) }}" method="post">
                    @csrf
                    <div class="payment">
                        <input class="payment__toggle" type="radio" id="payment-open" {{ session('payment_method') ? 'checked' : '' }}>
                        <label class="payment__select" for="payment-open">
                            <span class="payment__placeholder">選択してください</span>
                        </label>
                        <div class="payment__select-list">
                            <input class="payment__select-item" type="radio" id="payment1" name="payment_method" value="1" {{ session('payment_method') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="payment__select-label" for="payment1">コンビニ払い</label>
                            <input class="payment__select-item" type="radio" id="payment2" name="payment_method" value="2" {{ session('payment_method') == '2' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="payment__select-label" for="payment2">カード支払い</label>
                        </div>
                    </div>
                </form>
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
            <div class="form__button">
                <button class="form__button-submit" type="submit">購入する</button>
            </div>
        </div>
    </div>
@endsection