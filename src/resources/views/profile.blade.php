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
            @csrf
            <div class="profile__image">
                @if ($profile && $profile->icon_path)
                    <img class="profile__image-preview" src="{{ asset('storage/' . $profile->icon_path) }}" alt="プロフィール画像">
                @else
                    <div class="profile__image-default"></div>
                @endif
                <div class="profile__image-select">
                    <label class="image__select-item" for="profile_image">画像を選択する</label>
                    <input type="file" name="image" id="profile_image" hidden>
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <label class="form__label-item">ユーザー名</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="name" value="{{ old('name', $profile->name ?? "") }}">
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <label class="form__label-item">郵便番号</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="post_code" value="{{ old('post_code', $profile->post_code ?? "") }}">
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <label class="form__label-item">住所</label>
                </div>
                <div class="form__group-content">
                    <input class="form__input" type="text" name="address" value="{{ old('address', $profile->address ?? "") }}">
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
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