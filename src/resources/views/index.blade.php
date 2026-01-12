@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div class="tabs">
        <a href="{{ route('items.index', ['keyword' => $keyword]) }}" class="item__tab{{ $tab === 'all' ? '--active' : '' }}">
            おすすめ
        </a>
        <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}" class="item__tab{{ $tab === 'mylist' ? '--active' : '' }}">
            マイリスト
        </a>
    </div>
    @if ($tab === 'mylist' && !Auth::check())
        {{-- 何も表示しない --}}
    @else
        <div class="items">
            @foreach ($items as $item)
                <div class="item-card">
                    <a href="{{ route('items.detail', $item->id) }}">
                        <img class="item-card__img" src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                    </a>
                    <div class="item-card__info">
                        <p class="item-card__name">
                            {{ $item->name }}
                        </p>
                        @if ($item->status === 2)
                            <p class="item-card__status"> Sold</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection