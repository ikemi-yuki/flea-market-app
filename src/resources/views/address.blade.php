@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
    <div class="address-change">
        <div class="address-change__header">
            <h2 class="address-change__header-title">住所の変更</h2>
        </div>
        <form class="address-change__form" action="{{ route('purchase.address.update') }}" method="post">
            @csrf
            <div class="form__group">
                <div class="form__group-title">
                    <label class="form__label-item">郵便番号</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="shipping_post_code" value="{{ old('shipping_post_code')}}">
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <label class="form__label-item">住所</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="shipping_address" value="{{ old('shipping_address') }}">
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <label class="form__label-item">建物名</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="shipping_building" value="{{ old('shipping_building') }}">
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
@endsection