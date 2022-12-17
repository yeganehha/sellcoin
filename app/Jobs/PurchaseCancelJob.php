<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Order\PurchaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class PurchaseCancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    Public int $orderId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        try {
            PurchaseService::cancel($this->orderId);
        } catch (InvalidParameterException $exception){}
    }
}
