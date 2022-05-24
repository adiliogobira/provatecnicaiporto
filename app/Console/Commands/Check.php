<?php

namespace App\Console\Commands;

use App\Http\Controllers\BinanceController;
use Illuminate\Console\Command;

class Check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:checkAvgBigPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica se o preço da última crypto.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $Binance = new BinanceController();
        $this->info($Binance->checkPrice());
    }
}
