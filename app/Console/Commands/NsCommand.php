<?php

namespace App\Console\Commands;

use App\Libs\NsSolveLib;
use App\Libs\NsSolveLibManual;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NsCommand extends Command
{
    protected $signature = 'ns:solve {id}';
    protected $description = '指定のIDの問題を解く';

    public function handle()
    {
        $id = $this->argument('id');
        $question = Question::where('id', $id)->first();
        echo "Q:\n";
        echo "------------------\n";
        $this->display_field(unserialize($question->data));
        echo "------------------\n";
        echo "\n";

        $dt_start = new Carbon();
        $nsl = new NsSolveLib();
        $result = $nsl->solveBackTrack($question);
        $dt_end = new Carbon();

        echo "A:\n";
        echo "------------------\n";
        $this->display_field($result);
        echo "------------------\n";
        echo "\n";
        echo "実行時間 : " . $dt_start->diffInSeconds($dt_end) . "sec";
        return Command::SUCCESS;
    }

    public function display_field($field)
    {
        if(count($field) != 81){
            return;
        }
        for ($j = 0; $j < 9; $j++) {
            for ($i = 0; $i < 9; $i++) {
                $index = $j * 9 + $i;
                echo $field[$index];
                echo ",";
            }
            echo "\n";
        }
    }
}
