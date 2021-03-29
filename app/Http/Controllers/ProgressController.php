<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProgressController extends Controller {

    private $composerLog;

    private function doCommand(Request $request) {

        $tag    = $request->input('tag');
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
                die;
            break;
        }
        if (strlen($tag) !== 8) {
            if (!preg_match('/^v\d+\.\d+/', $tag)) {
                return false;
            }
        }

        $process = new Process('cd ../../' . $path . ' && git status');

        $this->composerLog;
        $process->run(function ($type, $buffer) {
            $this->composerLog[] = $buffer;
        });
        return ($this->composerLog);
    }

    public function getVersion($server, Request $request) {

        $request = new Request();
        $request->initialize(['tag' => 1, 'server' => $server]);

        $return = ($this->doCommand($request));
        return json_encode(["version" => $server,"return" =>$return]);
    }
}
