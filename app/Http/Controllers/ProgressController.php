<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProgressController extends Controller
{
    private $composerLog;
    public function doCommand(Request $request){
        $tag = $request->input('tag');
        $server = $request->input('server');
        switch ($server) {
            case 'frontend':
                $path = 'client_web';
            break;
            case 'backend':
                $path = 'client_backend';
            break;
            case 'api':
                $path = 'server_api';
            break;
            case 'socket':
                $path = 'server_socket';
            break;
            default:
                # code...
            break;
        }
        if(strlen($tag) !== 8){
            if(!preg_match('/^v\d+\.\d+/',$tag)){
                return redirect('/404');
            }
        }

        $process = new Process('cd ../../'.$path.' && git fetch && git reset --hard '.$tag);
        $this->composerLog;
        $process->run(function($type, $buffer) {
            $this->composerLog[] = $buffer;
        });
        dump( $this->composerLog);
        dd($process->isSuccessful());
        return ;
    }
}
