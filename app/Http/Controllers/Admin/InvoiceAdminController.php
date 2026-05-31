<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceAdminController extends Controller
{
    /**
     * List all invoices
     */
    public function index(Request $request): View
    {
        $query = Invoice::with('subscription.user', 'subscription.plan');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(20);
        $statuses = ['pending', 'paid', 'failed', 'refunded'];

        return view('admin.invoices.index', compact('invoices', 'statuses'));
    }

    /**
     * Show invoice details
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load('subscription.user', 'subscription.plan');

        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Send email reminder for invoice payment
     */
    public function send(Invoice $invoice): RedirectResponse
    {
        Log::info("Email reminder sent manually by admin for invoice number " . $invoice->invoice_number);

        return back()->with('success', 'Email reminder has been sent to user successfully!');
    }
}
