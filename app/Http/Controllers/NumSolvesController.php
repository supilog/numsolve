<?php

namespace App\Http\Controllers;

use App\Libs\NsDataLib;
use App\Libs\NsSolveLib;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NumSolvesController extends Controller
{
    protected NsDataLib $ndl;
    protected NsSolveLib $nsl;

    public function __construct()
    {
        $this->ndl = new NsDataLib();
        $this->nsl = new NsSolveLib();
    }

    /**
     * [画面]トップページ
     */
    public function index(Request $request)
    {
        $data = [
            'questions' => Question::paginate(config('ns.pagenate'))
        ];
        return view('index', $data);
    }

    /**
     * [画面]問題追加
     */
    public function create(Request $request)
    {
        $data = [];
        return view('create', $data);
    }

    /**
     * [処理]問題追加
     */
    public function store(Request $request)
    {
        if (empty($request->nsdata)) {
            return redirect()->route('create');
        }

        $this->ndl->storeNsData($request->nsdata);

        return redirect()->route('index');
    }

    public function show(Request $request, $key)
    {
        $question = Question::where('key', $key)->first();
        if (empty($question)) {
            return redirect()->route('index');
        }

        $data = [
            'question' => $question,
            'nsdata_json' => json_encode(unserialize($question->data))
        ];
        return view('show', $data);
    }

    public function solve(Request $request)
    {
        $question = Question::where('key',$request->key)->first();
        $answer_data = $this->nsl->solveBackTrack($question);
        $ret = [
            'status' => 'success',
            'question' => [
                'data' => unserialize($question->data)
            ],
            'answer' => [
                'data' => $answer_data
            ]
        ];
        return $ret;
    }
}
