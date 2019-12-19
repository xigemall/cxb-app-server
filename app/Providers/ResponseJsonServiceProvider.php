<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseJsonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // api 接口返回
        Response::macro('data', function ($data = [], int $code = 200, string $msg = 'ok', array $headers = []) {
            $result = [
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
            ];
            return Response::make($result, 200, $headers);
        });
    }
}
