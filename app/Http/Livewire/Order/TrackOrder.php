<?php

namespace App\Http\Livewire\Order;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Services\Order\PurchaseService;
use Livewire\Component;

class TrackOrder extends Component
{
    public $message ;

    public Order $order ;
    public $error = false ;
    public $wait = false ;

    public function mount(Order $order)
    {
        $this->order = $order;
        if ( $this->order->status == OrderStatusEnum::Wait) {
            $cancelTime = $this->order->created_at->addMinutes(config('setting.purchase.wait_for_confirm' , 0));
            $this->wait = now()->diffInSeconds($cancelTime) + 1 ;
        }
    }

    public function confirm()
    {
        $this->error = false;
        try {
            $this->order = PurchaseService::confirm($this->order);
        } catch (\Exception $exception){
            $this->error = $exception->getMessage();
        }
    }
    public function cancel()
    {
        $this->error = false;
        try {
            $this->order = PurchaseService::cancel($this->order);
        } catch (\Exception $exception){
            $this->error = $exception->getMessage();
        }
    }
    public function render()
    {
        return view('livewire.order.track-order')->layout('layouts.app');
    }
}
