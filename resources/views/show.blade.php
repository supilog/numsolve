@extends('layouts.app')

@section('title', '問題|'.config('app.name'))

@section('content')
<div id="ns-box">
    <table>
        @for($i = 0; $i < 9; $i++)
        <tr>
            @for($j = 0; $j < 9; $j++)
            <?php $index = $i * 9 + $j; ?>
            <td id="ns-box-cell-{{ $index }}" class="ns-box-cell" data-index="{{ $index  }}">&nbsp;</td>
            @endfor
            </tr>
        @endfor
    </table>
    <div class="my-6 text-center">
        <button id="saveBtn" class="px-2 py-1 text-gray-500 border border-gray-500 font-semibold rounded hover:bg-gray-100" disabled>保存する</button>
        <button id="solveBtn" class="px-2 py-1 text-green-500 border border-green-500 font-semibold rounded hover:bg-green-100">解く</button>
    </div>
</div>
<div id="ns-data" data-json="{{ $nsdata_json }}" data-key="{{ $question->key }}"></div>
@endsection

@section('js')
@vite('resources/js/show.js')
@endsection
