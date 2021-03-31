<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProgressController extends Controller {

    private $path_backend;
    private $path_frontend;
    private $path_api;
    private $path_socket;
    private $secret_key;

    public function __construct() {
        $this->path_backend  = config('deploy.path_backend');
        $this->path_frontend = config('deploy.path_frontend');
        $this->path_api      = config('deploy.path_api');
        $this->path_socket   = config('deploy.path_socket');
        $this->secret_key    = config('deploy.secret_key');
    }

    private $composerLog;

    /**
     * @param         $server
     * @param Request $request
     *
     * @return false|string
     */
    public function getVersion($server, Request $request) {

        $request = new Request();
        $request->initialize(['tag' => 1, 'server' => $server]);

        $return = ($this->doCommandGetVersion($request));

        return json_encode(["server" => $server, "return" => $return]);
    }

    /**
     * @param         $server
     * @param Request $request
     *
     * @return false|mixed|string
     */
    public function doDeploy($server, Request $request) {
        $tag = $request->input('tag');

        $request = new Request();
        $request->initialize(['server' => $server, 'tag' => $tag]);

        $return = ($this->doCommandDeploy($request));

        return json_encode(["server" => $server, "return" => $return]);
    }

    /**
     * @param $server
     *
     * @return string|null
     */
    private function getPath($server): ?string {

        switch ($server) {
            case 'frontend':
                $path = $this->path_frontend;
            break;
            case 'backend':
                $path = $this->path_backend;
            break;
            case 'api':
                $path = $this->path_api;
            break;
            case 'socket':
                $path = $this->path_socket;
            break;
            default:
                $path = null;
            break;
        }

        return $path;
    }

    /**
     * @param Request $request
     *
     * @return null
     */
    private function doCommandGetVersion(Request $request) {

        $tag    = $request->input('tag');
        $server = $request->input('server');
        $path   = $this->getPath($server);

        if ($path === null) {
            return null;
        }

        if (strlen($tag) !== 7) {
            if (!preg_match('/^v\d+\.\d+/', $tag)) {
                // return false;
            }
        }

        $process = new Process('cd ../../' . $path . ' && git describe --tags');

        $this->composerLog;
        $process->run(function ($type, $buffer) {
            $this->composerLog[] = $buffer;
        });

        return ($this->composerLog);
    }

    /**
     * @param Request $request
     *
     * @return null
     */
    private function doCommandDeploy(Request $request) {

        $tag    = $request->input('tag');
        $server = $request->input('server');
        $path   = $this->getPath($server);

        if ($path === null) {
            return null;
        }

        if (strlen($tag) !== 7) {
            if (!preg_match('/^v\d+\.\d+/', $tag)) {
                return false;
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
}
