@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <div class="profile">
        <div class="profile__header">
            <h2 class="profile__header-title">プロフィール設定</h2>
        </div>
        <form class="profile__form" action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <div class="profile__image">
                    <img id="profile-preview" class="profile__image-preview
                        @if (!($profile && $profile->icon_path)) is-hidden @endif"
                        src="{{ $profile && $profile->icon_path ? $profile->icon_url : '' }}" alt="プロフィール画像">
                    <div id="profile-default" class="profile__image-default
                        @if ($profile && $profile->icon_path) is-hidden @endif">
                    </div>
                    <div class="profile__image-select">
                        <label class="image__select-item" for="profile-image">画像を選択する</label>
                        <input type="file" name="icon_path" id="profile-image" hidden>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-group__title">
                    <label class="form__label-item">ユーザー名</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="name" value="{{ old('name', $profile->name ?? "") }}">
                </div>
            </div>
            <div class="form-group">
                <div class="form-group__title">
                    <label class="form__label-item">郵便番号</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="post_code" value="{{ old('post_code', $profile->post_code ?? "") }}">
                </div>
            </div>
            <div class="form-group">
                <div class="form-group__title">
                    <label class="form__label-item">住所</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="address" value="{{ old('address', $profile->address ?? "") }}">
                </div>
            </div>
            <div class="form-group">
                <div class="form-group__title">
                    <label class="form__label-item">建物名</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="building" value="{{ old('building', $profile->building ?? "") }}">
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('profile-image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            const preview = document.getElementById('profile-preview');
            const defaultImage = document.getElementById('profile-default');

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('is-hidden');

            if (defaultImage) {
                defaultImage.classList.add('is-hidden');
            }
        });
    </script>
@endpush