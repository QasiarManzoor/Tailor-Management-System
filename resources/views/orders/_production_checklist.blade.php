<div class="card card-soft mb-4">
    <div class="card-body p-4">
        <div class="section-title mb-3">Production Checklist</div>
        <form method="POST" action="{{ route('orders.checklist.update', $order) }}">
            @csrf
            @method('PATCH')
            <div class="row g-2">
                @foreach ($order->checklistItems as $item)
                    <div class="col-md-6">
                        <label class="list-chip w-100 justify-content-start">
                            <input type="checkbox" name="items[]" value="{{ $item->id }}" class="form-check-input mt-0" @checked($item->is_done)>
                            <span>{{ $item->label }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <button class="btn btn-sm btn-dark mt-3">Update Checklist</button>
        </form>
    </div>
</div>
