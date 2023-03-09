<?php

namespace App\Libs;

use App\Models\Question;

class NsSolveLib
{
    /**
     * バックトラック法で解く
     */
    public function solveBackTrack(Question $question): array
    {
        return array();
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

}
