<div>
    <x-slot name="title">{{ 'Update Wallet'}}</x-slot>
    <x-slot name="backRoute">{{ route('platforms.index')}}</x-slot>
    @if($message)
        <div class="alert alert-success">
            {{$message}}
        </div>
    @endif
    <div class="row">

        <div class="col-md-12 row mb-3">
            <label for="version_id" class="col-form-label text-md-end">New Deposit Tether: {{ number_format($showAmount,8) }}</label>
        </div>

        <div class="col-md-6 row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">Amount</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('amount') is-invalid @enderror" wire:model="amount"  value="{{ $amount }}" autocomplete="off" autofocus>
                @error('amount')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6 row mb-3">
            <label for="version_id" class="col-md-4 col-form-label text-md-end">Transaction Type</label>

            <div class="col-md-6">
                <select id="version_id" class="form-control @error('type') is-invalid @enderror" wire:model="type">
                    <option value="deposit" @selected($type  == "deposit")>+ Deposit</option>
                    <option value="withdraw" @selected($type  == "withdraw")>- Withdraw</option>
                </select>
                @error('type')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12 mt-4 justify-content-center align-items-center d-flex border-1">
            <button type="button" class="btn btn-success mt-4" wire:loading.attr="disabled"
                    wire:click.prevent="save">
                Submit
            </button>
        </div>
    </div>
</div>
