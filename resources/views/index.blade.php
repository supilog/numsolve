@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
<div class="grid grid-cols-[repeat(4,56px)] place-content-center h-36 gap-4 mt-12">
    <a href="{{ route('create') }}">
    <div class="tooltip text-center p-4 w-14 h-14 flex items-center justify-center shadow-lg rounded-lg border border-sky-500 text-sky-500 hover:bg-sky-100">
        <i class=" lar la-plus-square"></i>
        <div class="hidden description">新しい問題を追加する</div>
    </div>
    </a>
    <a href="{{ route('create') }}">
    <div class="tooltip text-center p-4 w-14 h-14 flex items-center justify-center shadow-lg rounded-lg bg-sky-500">
        <i class=" lar la-plus-square"></i>
        <div class="hidden description">新しい問題を追加する</div>
    </div>
    </a>
    <a href="{{ route('create') }}">
    <div class="tooltip text-center p-4 w-14 h-14 flex items-center justify-center shadow-lg rounded-lg bg-sky-500">
        <i class=" lar la-plus-square"></i>
        <div class="hidden description">新しい問題を追加する</div>
    </div>
    </a>
    <a href="{{ route('create') }}">
    <div class="tooltip text-center p-4 w-14 h-14 flex items-center justify-center shadow-lg rounded-lg bg-sky-500">
        <i class=" lar la-plus-square"></i>
        <div class="hidden description">新しい問題を追加する</div>
    </div>
    </a>
</div>

<div class="text-center">もんだい</div>
<div class="ns-pagenate">
{{ $questions->links() }}
</div>

<div class="grid grid-cols-[repeat(3,112px)] place-content-center gap-2">
    @foreach ($questions as $question)
    <a href="{{ route('show', $question->key) }}">
        <div class="p-1 w-28 h-14 flex items-center justify-center shadow-lg rounded-lg">
            問題{{ $question->id }}
        </div>
    </a>
    @endforeach
</div>
@endsection