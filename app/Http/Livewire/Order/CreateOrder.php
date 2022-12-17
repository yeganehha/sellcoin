<?php

namespace App\Http\Livewire\Order;

use App\Models\Platform;
use App\Services\Order\PurchaseService;
use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use Livewire\Component;

class CreateOrder extends Component
{
    public $platforms;
    public $platformId;
    public $platform;
    public $coins = [];
    public $coinId;
    public $coin;
    public $amount;
    public $price;
    public $message ;
    public $error = false ;

    protected $rules = [
        'platformId' => ['required' , 'numeric' , 'exists:platforms,id' ],
        'coinId' => ['required', 'string'],
        'amount' => ['required' , 'numeric' , 'min:0' ],
    ];

    public function updated($peroperty , $value )
    {
        $this->error = false;
        try {
            $this->message = null;
            if ( $peroperty == "amount" ) {
                $this->amountValidation();
            }
            $this->validateOnly($peroperty);
            if ( $peroperty == "platformId" ) {
                $this->platform = PlatformService::find($value);
                $this->coins = DriverService::coins($this->platform)->toArray();
                $this->coin = null;
                $this->amount = null;
            }
            if ( $peroperty == "coinId" ) {
                try {
                    $this->coin = DriverService::coin($this->platform, $value)->toArray();
                } catch ( \Exception $exception){
                    $this->coin = null;
                }
                $this->amount = null;
            }
            if ( $peroperty == "amount" ) {
                $this->price = PurchaseService::price( $this->coin , $value);
            }
        } catch (\Exception $exception){
            $this->error = $exception->getMessage();
            $this->reset(['price' , 'amount' , 'coin','coinId', 'coins' , 'platform' , 'platformId']);
        }
    }

    private function amountValidation()
    {
        if ($this->coin)
            $this->rules['amount'] =  ['required' , 'numeric', 'min:0' , 'max:'. PurchaseService::maximumAvailableAmount($this->platform , $this->coin)];
        else
            $this->rules['amount'] =  ['required' , 'numeric' , 'min:0' ];
    }


    public function purchase()
    {
        $this->error = false;
        $this->amountValidation();
        $this->validate();
        try {
            $order = PurchaseService::draft($this->platform, $this->coin, $this->amount);
            return $this->redirect(route('orders.track' , $order));
        } catch (\Exception $exception){
            $this->error = $exception->getMessage();
        }
    }

    public function mount()
    {
        $this->platforms = PlatformService::listPlatforms();
    }

    public function render()
    {
        return view('livewire.order.create-order')->layout('layouts.app');
    }
}
