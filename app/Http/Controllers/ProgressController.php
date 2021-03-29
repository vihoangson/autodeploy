<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProgressController extends Controller {

    private $composerLog;

    private function doCommandGetVersion(Request $request) {

        $tag    = $request->input('tag');
        $server = $request->input('server');
        $path   = $this->getPath($server);

        if ($path === null) {
            return null;
        }

        if (strlen($tag) !== 8) {
            if (!preg_match('/^v\d+\.\d+/', $tag)) {
                //return false;
            }
        }

        $process = new Process('cd ../../' . $path . ' && git describe --tags');

        $this->composerLog;
        $process->run(function ($type, $buffer) {
            $this->composerLog[] = $buffer;
        });

        return ($this->composerLog);
    }

    private function doCommandDeploy(Request $request) {

        $tag    = $request->input('tag');
        $server = $request->input('server');
        $path   = $this->getPath($server);

        if ($path === null) {
            return null;
        }

        if (strlen($tag) !== 8) {
            if (!preg_match('/^v\d+\.\d+/', $tag)) {
                //return false;
            }
        }

        $command = 'cd ../../' . $path . ' && git fetch && git reset --hard ' . $tag;

        $process = new Process($command);

        $this->composerLog;
        $process->run(function ($type, $buffer) {
            $this->composerLog[] = $buffer;
        });

        return ($this->composerLog);
    }

    public function getVersion($server, Request $request) {

        $request = new Request();
        $request->initialize(['tag' => 1, 'server' => $server]);

        $return = ($this->doCommandGetVersion($request));

        return json_encode(["server" => $server, "return" => $return]);
    }

    public function doDeploy($server, Request $request) {
        $tag = $request->input('tag');
        return $tag;
        $request = new Request();
        $request->initialize(['server' => $server, 'tag' => $tag]);

        $return = ($this->doCommandDeploy($request));

        return json_encode(["server" => $server, "return" => $return]);
    }

    private function getPath($server): ?string {

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
                $path = null;
            break;
        }

        return $path;
    }
}
