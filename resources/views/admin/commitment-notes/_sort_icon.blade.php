@if($sortField === $field)
    @if($sortDirection === 'asc')
        <i class="bx bx-up-arrow-alt ms-1"></i>
    @else
        <i class="bx bx-down-arrow-alt ms-1"></i>
    @endif
@else
    <i class="bx bx-sort ms-1 text-muted"></i>
@endif