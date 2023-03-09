<?php

namespace App\Libs;

use App\Models\Question;

class NsDataLib
{
    /**
     * 問題を登録する
     */
    public function storeNsData($nsdata_json): void
    {
        $nsdata = json_decode($nsdata_json);
        // 同じ問題が登録されている場合には処理しない
        if (Question::where('data', serialize($nsdata))->count() > 0) {
            return;
        }
        // 登録
        $question = new Question();
        $question->data = serialize($nsdata);
        $question->key = $this->makeNewQuestionKey(6);
        $question->save();
    }

    /**
     * 問題KEYを作成
     */
    public function makeNewQuestionKey($num): string
    {
        $str = 'abcdefghjkmnpqrstuvwxy3456789';
        return substr(str_shuffle(str_repeat($str, $num)), 0, $num);
    }

}
