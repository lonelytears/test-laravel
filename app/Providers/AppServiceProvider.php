<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 设置 String 长度
        Schema::defaultStringLength(191);

        // 监听 SQL 日志
        DB::listen(function ($query) {
            Log::info('db listen', [
                'sql' => str_replace('?', '"'.'%s'.'"', $query->sql),
                'bindings' => $query->bindings,
                'time' => $query->time
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
