<?php

namespace App\Libs;

use App\Exceptions\NumSolveException;
use App\Models\Question;

class NsSolveLibManual
{
    // 現在のフィールド状態
    protected $field = [];
    // 候補値配列
    protected $possible_values = [];
    // 完成フィールド
    protected $comple_field = [];

    /**
     * 人力で解く
     */
    public function solveManual(Question $question): array
    {
        // fieldの初期状態
        $field = unserialize($question->data);

        // 継続条件 : 新しい更新がある & ループ回数200回以内
        $cnt = 0;
        $update = true;
        while($update && $cnt < 200){
            echo "$cnt\n";
            // updateフラグ
            $update = false;
            // 候補値配列生成
            $possible_values = $this->getPossibleValues($field);
            // 候補値が1個になったものは確定処理
            foreach($possible_values as $possible_index => $possible_value){
                if(count($possible_value) == 1){
                    $field[$possible_index] = $possible_value[0];
                    $update = true;
                }
            }
            // 関連する2マスの候補値が2個である場合は、他の関連候補値から該当の2個を削除

            $cnt++;
        }
        dd($possible_values);
        return $this->field;
    }

    /**
     * フィールド全体の候補地を選定
     */
    public function getPossibleValues($field)
    {
        $size_x = config('ns.nmpl.size.x');
        $size_y = config('ns.nmpl.size.y');
        $ret = array();
        for ($j = 0; $j < $size_y; $j++) {
            for ($i = 0; $i < $size_x; $i++) {
                $point = [
                    'x' => $i,
                    'y' => $j
                ];
                $index = $this->coordinatesToIndex($point);
                if ($field[$index] == 0) {
                    $ret[$index] = $this->getPossibleValue($field, $point);
                }
            }
        }

        // 
        return $ret;
    }

    /**
     * 指定の座標の候補値を選定
     */
    public function getPossibleValue($field, $point)
    {
        $default = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
        $related_indexes = $this->getRelatedFieldIndex($point);
        $related_values = array();
        foreach ($related_indexes as $related_index) {
            if ($field[$related_index] != 0) {
                $related_values[$field[$related_index]] = 1;
            }
        }
        $keys = array_keys($related_values);
        sort($keys);
        $result = array_values(array_diff($default, $keys));
        if (count($result) == 0) {
            throw new NumSolveException('とんでもないエラー。バグです');
        }
        return $result;
    }

    /**
     * 座標を配列indexに置き換える
     * array('x' => x座標, 'y' => y座標) to index
     */
    public function coordinatesToIndex($point): int
    {
        return config('ns.nmpl.size.x') * $point['y'] + $point['x'];
    }

    /**
     * 座標から自身を除いた関連マスのindexリストを取得(横、縦、ブロック)
     */
    public function getRelatedFieldIndex($point): array
    {
        $size_x = config('ns.nmpl.size.x');
        $size_y = config('ns.nmpl.size.y');
        $checklist = array();
        // 横
        for ($i = 0; $i < $size_x; $i++) {
            $index = $this->coordinatesToIndex(['x' => $i, 'y' => $point['y']]);
            $checklist[$index] = 1;
        }
        // 縦
        for ($i = 0; $i < $size_y; $i++) {
            $index = $this->coordinatesToIndex(['x' => $point['x'], 'y' => $i]);
            $checklist[$index] = 1;
        }
        // ブロック
        $block_init['x'] = floor($point['x'] / 3) * 3;
        $block_init['y'] = floor($point['y'] / 3) * 3;
        for ($j = 0; $j < 3; $j++) {
            for ($i = 0; $i < 3; $i++) {
                $x = $block_init['x'] + $i;
                $y = $block_init['y'] + $j;
                $index = $this->coordinatesToIndex(['x' => $x, 'y' => $y]);
                $checklist[$index] = 1;
            }
        }
        // 自身のindexを除外
        $excludeIndex = $this->coordinatesToIndex($point);
        unset($checklist[$excludeIndex]);

        return array_keys($checklist);
    }
}
