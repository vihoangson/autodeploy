<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProgressController extends Controller
{
    private $composerLog;
    public function doCommand(){
        $process = new Process('git pull');
        // dd($process->run(function($type, $buffer) {
        //     $this->composerLog[] = $buffer;
        // }));
        // $this->info("Running 'composer install'");
        $this->composerLog;
        $process->run(function($type, $buffer) {
            $this->composerLog[] = $buffer;
        });
        dd( $this->composerLog);
        dd($process->isSuccessful());
        return ;
    }
}
