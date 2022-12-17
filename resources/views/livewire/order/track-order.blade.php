<div>
    <x-slot name="title">Track order #{{ $order->id }}</x-slot>
    <script>
        function moment() {
            return {
                seconds: 0,
                interval: "",
                init(seconds) {
                    this.seconds = seconds;
                    this.interval = setInterval(() => {
                        if (this.seconds === 0) {
                            clearInterval(this.interval);
                            window.location.reload();
                        } else
                            this.seconds--;
                    }, 1000);
                },
                getTimeElapsed() {
                    return this.seconds;
                }
            }
        }
    </script>
    <div class="alert alert-warning">
        <strong>Your purchase is not complete!</strong>
        <div  x-data="moment" x-init="init(40)">
            Your purchase is going to be `cancel` if you do not confirm purchase in <span x-text="getTimeElapsed"></span> seconds!
        </div>
    </div>
    @if($error)
        <div class="alert alert-danger">
            Some Error happened!
            <div>{{ $error }}</div>
        </div>
    @endif
    @if($message)
        <div class="alert alert-success">
            {{$message}}
        </div>
    @endif
    <table class="table table-striped">
        <tr>
            <td colspan="2">
                <H5>Order</H5>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <strong>Order ID</strong>: #{{ $order->id }}
            </td>
            <td width="50%">
                <strong>Order status</strong>: @include('layouts.status' , ['value' => $order->status])
                @if( $order->transaction )
                <strong>Track Number</strong>: {{ $order->transaction }}
                @endif
            </td>
        </tr>
        <tr>
            <td>
                <strong>Amount</strong>: {{ $order->amount }} <small>{{ $order->coin_symbol }}</small>
            </td>
            <td>
                <strong>Price</strong>: ${{ number_format($order->price , 8) }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <H5>Coin</H5>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Coin name</strong>: {{ $order->coin_name }}
            </td>
            <td>
                <strong>Coin price</strong> <small>(price when bought)</small>: ${{ number_format($order->coin_price , 8) }}
            </td>
        </tr>
    </table>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-3">
            <button class="btn btn-danger w-100" wire:loading.attr="disabled"
                    wire:click.prevent="cancel">Cancel purchase</button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success w-100" wire:loading.attr="disabled"
                    wire:click.prevent="confirm">Confirm purchase</button>
        </div>
    </div>
</div>
