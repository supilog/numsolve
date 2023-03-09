@extends('layouts.app')

@section('title', '問題追加|'.config('app.name'))

@section('content')
<div id="ns-box">
    <table>
        @for($i = 0; $i < 9; $i++)
        <tr>
            @for($j = 0; $j < 9; $j++)
            <?php $index = $i * 9 + $j; ?>
            <td id="ns-box-cell-{{ $index }}" class="ns-box-cell" data-index="{{ $index  }}" data-modal-target="numInputModal" data-modal-toggle="numInputModal">&nbsp;</td>
            @endfor
            </tr>
        @endfor
    </table>
    <div class="my-6 text-center">
        <button id="clearBtn" class="px-2 py-1 text-gray-500 border border-gray-500 font-semibold rounded hover:bg-gray-100">値をクリア</button>
        <button id="storeBtn" class="px-2 py-1 text-red-500 border border-red-500 font-semibold rounded hover:bg-red-100">とうろくする</button>
    </div>
</div>
<!-- Main modal -->
<div id="numInputModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
    <div class="relative w-full h-full max-w-xs md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    値の入力
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="numInputModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-3 gap-4">
                    @for($i = 1; $i < 10; $i++)
                    <button class="modal-btn text-center text-green-500 border border-green-500 font-semibold rounded hover:bg-green-100 rounded px-4 py-2" data-number="{{ $i }}" data-modal-hide="numInputModal">{{ $i }}</button>
                    @endfor
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <button class="modal-btn text-center text-green-500 border border-green-500 font-semibold rounded hover:bg-green-100 rounded px-4 py-2" data-number="0" data-modal-hide="numInputModal">EMPTY</button>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="ns-store" method="post" action="{{ route('store') }}">
    <input id="csrf" type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input id="ns-data" type="hidden" name="nsdata" value="" />
</form>
@endsection

@section('js')
@vite('resources/js/create.js')
@endsection
