<?php

namespace App\Console\Commands;

use App\Http\Controllers\BinanceController;
use Illuminate\Console\Command;

class Save extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:saveBidPriceOnDataBase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coleta o preço medio atual e salva no banco.';

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
        $this->info($Binance->savePrice());
    }
}
