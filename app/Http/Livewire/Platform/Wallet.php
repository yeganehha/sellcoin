<?php

namespace App\Http\Livewire\Platform;

use App\Models\Platform;
use App\Services\Platform\DriverService;
use App\Services\Platform\PlatformService;
use Livewire\Component;

class Wallet extends Component
{
    public $platform;
    public $message ;
    public $type = 'deposit';
    public $amount;
    public $showAmount;

    protected $rules = [
        'amount' => ['required' , 'numeric' ],
        'type' => ['required', 'in:deposit,withdraw'],
    ];

    public function updated($peroperty , $value )
    {
        $this->message = null;
        $this->amountValidation();
        if ( $this->type == "deposit")
            $this->showAmount = $this->platform->deposit_tether + $this->amount;
        else
            $this->showAmount = $this->platform->deposit_tether - $this->amount;

    }


    public function mount(Platform $platform)
    {
        $this->platform = $platform;
        $this->showAmount = $this->platform->deposit_tether;
    }

    public function save()
    {
        $this->amountValidation();
        if ( $this->type == "withdraw" and  $this->platform = PlatformService::withdrawTether($this->platform,$this->amount) ) {
            $this->message = "Withdraw successfully.";
            $this->amount = "";
        } elseif ( $this->type == "deposit" and $this->platform = PlatformService::depositTether($this->platform,$this->amount) ) {
            $this->message = "Deposit successfully.";
            $this->amount = "";
        }
        $this->showAmount = $this->platform->deposit_tether;

    }
    private function amountValidation()
    {
        if ($this->type == "withdraw")
            $this->rules['amount'] =  ['required' , 'numeric' , 'max:'.$this->platform->deposit_tether];
        else
            $this->rules['amount'] =  ['required' , 'numeric' ];
        $this->validate();
    }

    public function render()
    {
        return view('livewire.platform.wallet')->layout('layouts.app');
    }
}
