@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
    <div class="sell">
        <div class="sell__header">
            <h2 class="sell__header-title">商品の出品</h2>
        </div>
        <form class="sell__form" action="{{ route('sell.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">商品画像</label>
                <div class="form-image">
                    <label class="image-label" for="item_image">画像を選択する</label>
                    <input type="file" name="image_path" id="item_image" hidden>
                </div>
            </div>
            <h3 class="form-group__title">商品の詳細</h3>
            <div class="form-group">
                <label class="form-label">カテゴリー</label>
                <div class="category-group">
                    @foreach ($categories as $category)
                        <label class="category-label">
                            <input class="category-input" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ collect(old('categories', []))->contains($category->id) ? 'checked' : '' }}>
                            <span class="category-tag">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">商品の状態</label>
                <div class="condition">
                    <input class="condition__toggle" type="checkbox" id="condition-open" hidden>
                    <label class="condition__select" for="condition-open">
                        {{ old('condition') == 1 ? '良好' : '' }}
                        {{ old('condition') == 2 ? '目立った傷や汚れなし' : '' }}
                        {{ old('condition') == 3 ? 'やや傷や汚れあり' : '' }}
                        {{ old('condition') == 4 ? '状態が悪い' : '' }}
                        {{ !old('condition') ? '選択してください' : '' }}</label>
                    <div class="condition__select-list">
                        <input class="condition__select-item" type="radio" id="condition1" name="condition" value="1" {{ old('condition') == '1' ? 'checked' : '' }}>
                        <label class="condition__select-label" for="condition1">良好</label>
                        <input class="condition__select-item" type="radio" id="condition2" name="condition" value="2" {{ old('condition') == '2' ? 'checked' : '' }}>
                        <label class="condition__select-label" for="condition2">目立った傷や汚れなし</label>
                        <input class="condition__select-item" type="radio" id="condition3" name="condition" value="3" {{ old('condition') == '3' ? 'checked' : '' }}>
                        <label class="condition__select-label" for="condition3">やや傷や汚れあり</label>
                        <input class="condition__select-item" type="radio" id="condition4" name="condition" value="4" {{ old('condition') == '4' ? 'checked' : '' }}>
                        <label class="condition__select-label" for="cond4">状態が悪い</label>
                    </div>
                </div>
            </div>
            <h3 class="form-group__title">商品名と説明</h3>
            <div class="form-group">
                <label class="form-label">商品名</label>
                <input class="form-input" type="text" name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">ブランド名</label>
                <input class="form-input" type="text" name="brand" value="{{ old('brand') }}">
            </div>
            <div class="form-group">
                <label class="form-label">商品の説明</label>
                <textarea class="form-textarea" name="description">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">販売価格</label>
                <div class="price-input">
                    <span class="price-input__yen">¥</span>
                    <input class="price-input__field" type="number" name="price" value="{{ old('price') }}">
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">出品する</button>
            </div>
        </form>
    </div>
@endsection