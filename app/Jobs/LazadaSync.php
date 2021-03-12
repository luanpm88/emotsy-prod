<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Acelle\Model\Source;
use Acelle\Model\Customer;


class LazadaSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lazadaSource;
    protected $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Source $lazadaSource, Customer $customer)
    {
        //
        $this->lazadaSource = $lazadaSource;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // import all lazada products
        $this->lazadaSource->importProducts($this->customer);
    }
}
