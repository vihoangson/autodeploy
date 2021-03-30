<?php

namespace App\Http\Middleware;

use App\Mail\ForgotPass;
use App\Models\SecretKey;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class APIToken {

    private $token = '';
    /**
     * @var string[]
     */
    private $data_role;
    /**
     * @var \string[][]
     */
    private $data_role_uri;

    public function __construct() {

    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $secret = $request->header('secret');
        if($secret !== env('SECRET_KEY','5432112345')){
            throw new \Exception('wrong secret');
        }
        return $next($request);
    }
}

