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
                <h3 class="purchase-payment__title">支払い方法</h3>
                <div class="payment">
                    <input class="payment__select" type="checkbox" id="payment-toggle" hidden>
                    <label class="payment__select-label" for="payment-toggle">
                        <span class="payment__select-text" id="payment-text">
                            @if (!empty($paymentMethod))
                                {{ $paymentMethod == 1 ? 'コンビニ払い' : 'カード支払い' }}
                            @else
                                選択してください
                            @endif
                        </span>
                    </label>
                    <ul class="payment__select-list">
                        <li class="payment__select-item" data-value="1" data-selected="{{ $paymentMethod == 1 ? 'true' : 'false' }}">コンビニ払い</li>
                        <li class="payment__select-item" data-value="2" data-selected="{{ $paymentMethod == 2 ? 'true' : 'false' }}">カード支払い</li>
                    </ul>
                </div>
            </div>
            <div class="purchase-address">
                <div class="purchase-address__wrapper">
                    <h3 class="purchase-address__title">配送先</h3>
                    <form class="purchase-address__form"  action="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" method="get">
                        <input type="hidden" name="payment_method" id="payment-method-for-address" value="{{ $paymentMethod }}">
                        <button class="purchase-address__change-link" type="submit">変更する</button>
                    </form>
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
                            <th class="subtotal-table__header">支払い方法</th>
                            <td class="subtotal-table__payment" id="subtotal-payment">
                                {{ $paymentMethod == 2 ? 'カード支払い' : 'コンビニ払い' }}
                            </td>
                        </tr>
                    </table>
                </div>
                <input type="hidden" name="payment_method" id="payment-input" value="{{ $paymentMethod }}">
                <div class="form__button">
                    <button class="form__button-submit" type="submit">購入する</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.payment__select-item').forEach(item => {
                if (item.dataset.selected === 'true') {
                    item.classList.add('is-selected');
                }
            });
        });

        document.querySelectorAll('.payment__select-item').forEach(item => {
            item.addEventListener('click', () => {
                const value = item.dataset.value;
                const text = item.textContent;

                document.getElementById('payment-text').textContent = text;
                document.getElementById('subtotal-payment').textContent = text;
                document.getElementById('payment-input').value = value;
                document.getElementById('payment-method-for-address').value = value;

                document.querySelectorAll('.payment__select-item').forEach(i => {
                    i.classList.remove('is-selected');
                });

                item.classList.add('is-selected');
                document.getElementById('payment-toggle').checked = false;
            });
        });
    </script>
@endpush