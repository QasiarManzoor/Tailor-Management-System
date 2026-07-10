<div class="card card-soft mb-4">
    <div class="card-body p-4">
        <div class="section-title mb-3">Design And Fabric Photos</div>
        <form method="POST" action="{{ route('orders.attachments.store', $order) }}" enctype="multipart/form-data" class="row g-3 align-items-end mb-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label" for="type">Photo Type</label>
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                    @foreach ($attachmentTypes as $type)
                        <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>
                @include('partials.field-error', ['field' => 'type'])
            </div>
            <div class="col-md-5">
                <label class="form-label" for="photo">Photo</label>
                <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*" required>
                @include('partials.field-error', ['field' => 'photo'])
            </div>
            <div class="col-md-auto">
                <button class="btn btn-dark">Attach Photo</button>
            </div>
        </form>

        <div class="row g-3">
            @forelse ($order->attachments as $attachment)
                <div class="col-md-4">
                    <div class="record-card h-100">
                        <a href="{{ $attachment->url() }}" target="_blank" rel="noopener">
                            <img src="{{ $attachment->url() }}" alt="{{ $attachment->original_name }}" class="img-fluid rounded-4 border mb-2">
                        </a>
                        <div class="fw-semibold">{{ ucwords(str_replace('_', ' ', $attachment->type)) }}</div>
                        <div class="record-summary">{{ $attachment->original_name }}</div>
                        <form method="POST" action="{{ route('orders.attachments.destroy', [$order, $attachment]) }}" class="mt-2" onsubmit="return confirm('Remove this photo?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted mb-0">No design, fabric, or final photos attached yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
