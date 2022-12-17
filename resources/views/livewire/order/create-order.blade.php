<div>
    <x-slot name="title">Make new order</x-slot>
    @if($message)
        <div class="alert alert-success">
            {{$message}}
        </div>
    @endif
    <div class="row">

        <div class="col-md-6 row mb-3">
            <label for="version_id" class="col-md-4 col-form-label text-md-end">Exchange Platform</label>

            <div class="col-md-6">
                <select id="version_id" class="form-control @error('platformId') is-invalid @enderror" wire:model="platformId">
                    <option>Select platform</option>
                    @foreach($platforms as $item)
                        <option value="{{ $item['id'] }}" @selected($platformId == $item['id'])>{{ $item['name']  }}</option>
                    @endforeach
                </select>
                @error('platformId')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6 row mb-3">
            <label for="version_id" class="col-md-4 col-form-label text-md-end">Coin</label>

            <div class="col-md-6">
                <select id="version_id" class="form-control @error('coinId') is-invalid @enderror" wire:model="coinId">
                    <option>Select coin</option>
                    @foreach($coins as $item)
                        <option value="{{ $item['id'] }}" @selected($coinId == $item['id'])>{{ $item['name']  }}</option>
                    @endforeach
                </select>
                @error('coinId')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
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

        <div class="col-md-12 mt-4 justify-content-center align-items-center d-flex border-1">
            <button type="button" class="btn btn-success mt-4" wire:loading.attr="disabled"
                    wire:click.prevent="save">
                Submit
            </button>
        </div>
    </div>
</div>
