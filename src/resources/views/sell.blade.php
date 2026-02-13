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
                <label class="form-group__label">商品画像</label>
                <div class="form-item__image">
                    <img id="item-preview" class="item__image-preview is-hidden">
                    <label class="item__image-label" for="item-image">画像を選択する</label>
                    <input type="file" name="image_path" id="item-image" hidden>
                </div>
            </div>
            <h3 class="form-group__title">商品の詳細</h3>
            <div class="form-group">
                <label class="form-group__label">カテゴリー</label>
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
                <label class="form-group__label">商品の状態</label>
                <div class="condition">
                    <input class="condition__select" type="checkbox" id="condition-toggle" hidden>
                    <label class="condition__select-label" for="condition-toggle">
                        <span class="condition__select-text" id="select-text">選択してください</span>
                    </label>
                    <ul class="condition__select-list">
                        <li class="condition__select-item" data-value="1" data-selected="{{ old('condition') == 1 ? 'true' : 'false' }}">
                            良好
                        </li>
                        <li class="condition__select-item" data-value="2" data-selected="{{ old('condition') == 2 ? 'true' : 'false' }}">
                            目立った傷や汚れなし
                        </li>
                        <li class="condition__select-item" data-value="3" data-selected="{{ old('condition') == 3 ? 'true' : 'false' }}">
                            やや傷や汚れあり
                        </li>
                        <li class="condition__select-item" data-value="4" data-selected="{{ old('condition') == 4 ? 'true' : 'false' }}">
                            状態が悪い
                        </li>
                    </ul>
                    <input type="hidden" name="condition" id="condition-input" value="{{ old('condition') }}">
                </div>
            </div>
            <h3 class="form-group__title">商品名と説明</h3>
            <div class="form-group">
                <label class="form-group__label">商品名</label>
                <input class="form-group__input" type="text" name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label class="form-group__label">ブランド名</label>
                <input class="form-group__input" type="text" name="brand" value="{{ old('brand') }}">
            </div>
            <div class="form-group">
                <label class="form-group__label">商品の説明</label>
                <textarea class="form-textarea" name="description">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-group__label">販売価格</label>
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

@push('scripts')
    <script>
        document.getElementById('item-image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) return;

            const preview = document.getElementById('item-preview');

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('is-hidden');
        });

        document.addEventListener('DOMContentLoaded', () => {
            const selectedValue = document.getElementById('condition-input').value;

            if (!selectedValue) return;

            document.querySelectorAll('.condition__select-item').forEach(item => {
                if (item.dataset.value === selectedValue) {
                    item.classList.add('is-selected');
                    document.getElementById('select-text').textContent = item.textContent;
                }
            });
        });

        document.querySelectorAll('.condition__select-item').forEach(item => {
            item.addEventListener('click', () => {
                document.getElementById('select-text').textContent = item.textContent;
                document.getElementById('condition-input').value = item.dataset.value;
                document.querySelectorAll('.condition__select-item').forEach(i => {
                    i.classList.remove('is-selected');
                });
                item.classList.add('is-selected');
                document.getElementById('condition-toggle').checked = false;
            });
        });
    </script>
@endpush