@if ( $value == \App\Enums\OrderStatusEnum::Paid)
    <span class="badge bg-success">Paid</span>
@elseif ( $value == \App\Enums\OrderStatusEnum::Wait)
    <span class="badge bg-warning">Wait</span>
@elseif ( $value == \App\Enums\OrderStatusEnum::Cancel)
    <span class="badge bg-danger">Cancel</span>
@endif
