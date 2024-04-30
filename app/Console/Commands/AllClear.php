<?php

namespace App\Console\Commands;

use App\Traits\UpdateTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AllClear extends Command
{
    use UpdateTrait;

    protected $signature = 'all:clear';

    protected $description = 'All Data Cleared';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        Artisan::call('optimize:clear');
        cache()->flush();

        $this->delete_directory(base_path('bootstrap/cache/'),false);
        $this->delete_directory(base_path('storage/app/import/'),false);
        $this->delete_directory(base_path('storage/debugbar/'),false);
        $this->delete_directory(base_path('storage/framework/cache/data/'),false);
        $this->delete_directory(base_path('storage/framework/cache/laravel-excel/'),false);
        $this->delete_directory(base_path('storage/framework/views/'),false);
//        $this->delete_directory(base_path('storage/framework/sessions/'),false);
        $this->delete_directory(base_path('storage/logs/'),false);
        
        return 0;
    }
}