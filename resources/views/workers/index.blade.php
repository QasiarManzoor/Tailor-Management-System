@extends('layouts.app')

@section('title', 'Workers')
@section('page-title', 'Workers')
@section('page-subtitle', 'Manage cutters, stitchers, finishers, and shop staff for order assignment.')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-soft">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Add Worker</div>
                    <form method="POST" action="{{ route('workers.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="tailor">Tailor</option>
                                <option value="cutter">Cutter</option>
                                <option value="stitcher">Stitcher</option>
                                <option value="finishing">Finishing</option>
                                <option value="helper">Helper</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control" data-phone-input>
                        </div>
                        <input type="hidden" name="is_active" value="0">
                        <label class="list-chip mb-3">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input mt-0" checked>
                            Active
                        </label>
                        <button class="btn btn-dark w-100">Save Worker</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-soft">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Orders</th>
                            <th class="text-end">Wages Paid</th>
                            <th>Status</th>
                            <th class="text-end">Update</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($workers as $worker)
                            <tr>
                                <form method="POST" action="{{ route('workers.update', $worker) }}">
                                    @csrf
                                    @method('PUT')
                                    <td><input type="text" name="name" value="{{ $worker->name }}" class="form-control" required></td>
                                    <td><input type="text" name="role" value="{{ $worker->role }}" class="form-control" required></td>
                                    <td><input type="text" name="phone" value="{{ $worker->phone }}" class="form-control" data-phone-input></td>
                                    <td>
                                        <div class="fw-semibold">{{ number_format($worker->orders_count) }}</div>
                                        <div class="small text-muted">Rs. {{ number_format((float) $worker->orders_sum_total_amount, 0) }}</div>
                                    </td>
                                    <td class="text-end fw-semibold">Rs. {{ number_format((float) $worker->wage_payments_sum_amount, 0) }}</td>
                                    <td>
                                        <input type="hidden" name="is_active" value="0">
                                        <label class="list-chip">
                                            <input type="checkbox" name="is_active" value="1" class="form-check-input mt-0" @checked($worker->is_active)>
                                            Active
                                        </label>
                                    </td>
                                    <td class="text-end"><button class="btn btn-sm btn-outline-dark">Save</button></td>
                                </form>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No workers added yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-soft mt-4">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Record Worker Payment</div>
                    <form method="POST" action="" id="worker-payment-form" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-4">
                            <label for="worker_id" class="form-label">Worker</label>
                            <select id="worker_id" class="form-select" required>
                                <option value="">Choose worker</option>
                                @foreach ($workers as $worker)
                                    <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="order_id" class="form-label">Assigned Order</label>
                            <select id="order_id" name="order_id" class="form-select">
                                <option value="">General payment</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" id="payment_date" name="payment_date" value="{{ now()->toDateString() }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" min="1" step="1" id="amount" name="amount" class="form-control" data-integer-input required>
                        </div>
                        <div class="col-md-4">
                            <label for="payment_method" class="form-label">Method</label>
                            <select id="payment_method" name="payment_method" class="form-select" required>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method }}">{{ ucwords(str_replace('_', ' ', $method)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="note" class="form-label">Note</label>
                            <input type="text" id="note" name="note" class="form-control">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-dark">Record Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('worker-payment-form');
        var workerSelect = document.getElementById('worker_id');
        var orderSelect = document.getElementById('order_id');
        var routes = @json($workerPaymentRoutes);
        var orders = @json($workerOrderOptions);

        if (!form || !workerSelect || !orderSelect) {
            return;
        }

        function syncWorker() {
            var workerId = workerSelect.value;
            form.action = routes[workerId] || '';
            orderSelect.innerHTML = '<option value="">General payment</option>';

            (orders[workerId] || []).forEach(function (order) {
                var option = document.createElement('option');
                option.value = order.id;
                option.textContent = order.label;
                orderSelect.appendChild(option);
            });
        }

        workerSelect.addEventListener('change', syncWorker);
        form.addEventListener('submit', function (event) {
            if (!form.action) {
                event.preventDefault();
                workerSelect.focus();
            }
        });
    })();
</script>
@endpush
