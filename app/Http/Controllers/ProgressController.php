<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProgressController extends Controller
{
    private $composerLog;
    public function doCommand(Request $request){
        var_dump($request->input('tag'));
        $tag = $request->input('tag');
        if(strlen($tag) !== 8){
            if(!preg_match('/^v\d+\.\d+/',$tag)){
                return redirect('/404');
            }               
        }
 
        $process = new Process('cd ../../server_api && git fetch && git reset --hard '.$tag);
        $this->composerLog;
        $process->run(function($type, $buffer) {
            $this->composerLog[] = $buffer;
        });
        dump( $this->composerLog);
        dd($process->isSuccessful());
        return ;
    }
}
