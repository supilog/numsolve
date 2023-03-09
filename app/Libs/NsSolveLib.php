<?php

namespace App\Libs;

use App\Exceptions\NumSolveException;
use App\Models\Question;
use Exception;
use Illuminate\Support\Facades\Log;

class NsSolveLib
{
    // 完成フィールド
    protected $comple_field = [];

    /**
     * バックトラック法で解く
     */
    public function solveBackTrack(Question $question): array
    {
        // field, pointの初期状態
        $field = unserialize($question->data);
        $point = [
            'x' => 0,
            'y' => 0
        ];
        $this->nmplBackTrack($field, $point);
        return $this->comple_field;
    }

    /**
     * 人力で解く
     */
    public function solveManual(Question $question): array
    {
        return array();
    }

    /**
     * 座標を配列indexに置き換える
     * array('x' => x座標, 'y' => y座標) to index
     */
    public function coordinatesToIndex($point): int
    {
        return config('ns.nmpl.size.x') * $point['y'] + $point['x'];
    }

    public function nmplBackTrack($field, $point): void
    {
        // 空フィールドを探す
        $point = $this->getEmptyPoint($field, $point);
        // 空フィールドがない場合
        if (empty($point)) {
            // 終了前全チェック
            if (!$this->isComplete($field)) {
                // チェック失敗時にとくにやることがないのでとりあえずExceptionを投げておくことにする。
                throw new NumSolveException('エラー発生。おそらくバグですよ');
            }
            // 返却
            $this->comple_field = $field;
            return;
        }

        // 空フィールドがある場合は1から順に数字を入れてチェック
        for ($i = 0; $i < 9; $i++) {
            $num = $i + 1;
            if ($this->isPossibleValue($field, $point, $num)) {
                // 入力可能な場合は値をセットして、次のフィールドの探索
                $index = $this->coordinatesToIndex($point);
                $field[$index] = $num;
                $this->nmplBackTrack($field, $point);
            }
        }
    }

    /**
     * 次の空フィールドを取得
     * (現在位置も含む)
     * array $point 現在位置の座標
     * array $field 全体の値 
     */
    public function getEmptyPoint($field, $point): array
    {
        $size_x = config('ns.nmpl.size.x');
        $size_y = config('ns.nmpl.size.y');
        // 空フィールド判定
        while ($point['y'] < $size_y) {
            while ($point['x'] < $size_x) {
                if ($field[$this->coordinatesToIndex($point)] == 0) {
                    return $point;
                }
                $point['x']++;
            }
            $point['y']++;
            $point['x'] = 0;
        }

        // 空フィールドが存在しない場合、空配列を返却する
        return array();
    }

    /**
     * 入力可能な値かチェック
     */
    public function isPossibleValue($field, $point, $num): bool
    {
        // 0がセットされた要素数10の配列(値がない欄に0が入っているため)
        $checklist = array_fill(0, 10, 0);

        // 関連indexの取得
        $indexes = $this->getRelatedFieldIndex($point);
        foreach ($indexes as $index) {
            $checklist[$field[$index]] = 1;
        }

        // // 横の確認
        // for ($i = 0; $i < 9; $i++) {
        //     $index = $this->coordinatesToIndex(['x' => $i, 'y' => $point["y"]]);
        //     $checklist[$field[$index]] = 1;
        // }
        // // 縦チェック
        // for ($i = 0; $i < 9; $i++) {
        //     $index = $this->coordinatesToIndex(['x' => $point["x"], 'y' => $i]);
        //     $checklist[$field[$index]] = 1;
        // }

        // // ブロックチェック
        // $block_init['x'] = floor($point['x'] / 3) * 3;
        // $block_init['y'] = floor($point['y'] / 3) * 3;
        // for ($j = 0; $j < 3; $j++) {
        //     for ($i = 0; $i < 3; $i++) {
        //         $x = $block_init['x'] + $i;
        //         $y = $block_init['y'] + $j;
        //         $index = $this->coordinatesToIndex(['x' => $x, 'y' => $y]);
        //         $checklist[$field[$index]] = 1;
        //     }
        // }

        // チェックリストに1が入っていた場合、その値は存在するので不可
        if ($checklist[$num] == 1) {
            return false;
        }
        return true;
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

    /**
     * 完成チェック
     */
    public function isComplete($field): bool
    {
        $size_x = config('ns.nmpl.size.x');
        $size_y = config('ns.nmpl.size.y');
        // 空フィールド確認
        if (in_array(0, $field)) {
            return false;
        }
        // 関連マスに自身の値が存在しないことを全てのマスで確認
        $result = true;
        for ($j = 0; $j < $size_y; $j++) {
            for ($i = 0; $i < $size_x; $i++) {
                // 自身の座標
                $point = ['x' => $i, 'y' => $j];
                // 自身のINDEX
                $current_index = $this->coordinatesToIndex($point);
                // 自身の値
                $current_value = $field[$current_index];
                // 関連INDEX
                $relatedIndexes = $this->getRelatedFieldIndex($point);
                // 関連INDEXのマスに自身の値が入っていないかチェック
                foreach ($relatedIndexes as $relatedIndex) {
                    if ($current_value == $field[$relatedIndex]) {
                        $result = false;
                    }
                }
            }
        }
        return $result;
    }
}
