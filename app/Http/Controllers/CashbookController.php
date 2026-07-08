<?php

namespace App\Http\Controllers;

use App\Models\CashbookEntry;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CashbookController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->date('date') ?: today();

        $payments = Payment::with('order.customer')
            ->whereDate('payment_date', $date)
            ->latest()
            ->get();

        $entries = CashbookEntry::whereDate('entry_date', $date)
            ->latest()
            ->get();

        $paymentIncome = (float) $payments->sum('amount');
        $manualIncome = (float) $entries->where('type', 'income')->sum('amount');
        $expenses = (float) $entries->where('type', 'expense')->sum('amount');

        return view('cashbook.index', [
            'date' => $date,
            'payments' => $payments,
            'entries' => $entries,
            'paymentMethods' => Payment::METHODS,
            'expenseCategories' => CashbookEntry::EXPENSE_CATEGORIES,
            'summary' => [
                'income' => $paymentIncome + $manualIncome,
                'expenses' => $expenses,
                'net' => $paymentIncome + $manualIncome - $expenses,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entry_date' => ['required', 'date'],
            'type' => ['required', Rule::in(CashbookEntry::TYPES)],
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', Rule::in(Payment::METHODS)],
            'note' => ['nullable', 'string'],
        ]);

        CashbookEntry::create($validated);

        return redirect()
            ->route('cashbook.index', ['date' => $validated['entry_date']])
            ->with('success', 'Cashbook entry saved.');
    }
}
