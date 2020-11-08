<?php

namespace App\Console\Commands;

use App\Models\Customers;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $customersModel = new Customers();

        for ($i = 0; $i < 200; $i++) {
            $customersModel->newQuery()->insert([
                'userid'  => 1,
                'name'    => 'name' . $i,
                'phone'   => '+89 123456',
                'areaid'  => 1,
                'address' => '222',
                'status'  => 0
            ]);
        }
        $this->info('success');
    }
}
