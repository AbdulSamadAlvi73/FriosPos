<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\User;
use App\Models\FgpItem;
use App\Models\InventoryAllocation;
use Illuminate\Support\Facades\Auth;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index()
    {
        $data['invoices'] = Invoice::where('franchisee_id', Auth::user()->franchisee_id)->get();
        $data['invoiceCount'] = Invoice::where('franchisee_id', Auth::user()->franchisee_id)->count();

        return view('franchise_admin.payment.invoice.index', $data);
    }

    public function create()
    {
        $data['customers'] = Customer::where('franchisee_id', Auth::user()->franchisee_id)->get();
        $data['franchisee'] = '10';

        $flavors = FgpItem::all();

        $initialPopFlavors = [];
        foreach ($flavors as $flavor) {
            $initialPopFlavors[] = [
                'name' => $flavor->name,
                'image1' => $flavor->image1,
                'available' => $flavor->availableQuantity(),
            ];
        }

        $data['allocations'] = InventoryAllocation::with('flavor')
            ->join('fgp_items', 'fgp_items.fgp_item_id', '=', 'inventory_allocations.fgp_item_id')
            ->select('inventory_allocations.*', 'fgp_items.name', 'fgp_items.image1', 'fgp_items.case_cost')  // Select necessary columns from fgp_items
            ->get();


        return view('franchise_admin.payment.invoice.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'customer_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.flavor_id' => 'required|integer',
            'items.*.location' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'franchisee_id' => Auth::user()->franchisee_id,
            'customer_id' => $request->customer_id,
            'name' => $request->name,
            'taxable' => $request->has('apply_sales_tax'),
            'total_price' => 0,
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $lineTotal = $item['quantity'] * $item['price'];

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'flavor_id' => $item['flavor_id'],
                'inventory_allocation_id' => $item['location'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $lineTotal,
            ]);

            $total += $lineTotal;
        }

        if ($request->has('apply_sales_tax')) {
            $taxRate = floatval(config('your.tax_config.sales_tax_rate', 0));
            $total += $total * ($taxRate / 100);
        }

        $invoice->update(['total_price' => $total]);

        $corporateAdmin = User::where('user_id', 17)->first();
        Mail::to($invoice->customer->email ?? $corporateAdmin->email)
            ->send(new \App\Mail\InvoiceMail($invoice, $invoice->items, $invoice->franchisee->business_name));

        return redirect()->route('franchise.invoice.index')->with('success', 'Invoice created successfully.');
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items.flavor')->findOrFail($id);

        $subtotal = $invoice->items->sum(function ($item) {
            return (float) $item->total_price;
        });

        $tax = 0;
        $taxRate = 10;
        if ($invoice->taxable) {
            $tax = $subtotal * ($taxRate / 100);
        }

        $total = $subtotal + $tax;

        $customers = Customer::where('franchisee_id', Auth::user()->franchisee_id)->get();

        $allocations = InventoryAllocation::with('flavor')
            ->join('fgp_items', 'fgp_items.fgp_item_id', '=', 'inventory_allocations.fgp_item_id')
            ->select(
                'inventory_allocations.*',
                'fgp_items.name',
                'fgp_items.image1',
                'fgp_items.case_cost'
            )
            ->get();

        $franchisee = $taxRate;

        return view('franchise_admin.payment.invoice.edit', compact(
            'invoice',
            'franchisee',
            'customers',
            'allocations',
            'subtotal',
            'tax',
            'total'
        ));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'name' => 'required|string',
            'customer_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.flavor_id' => 'required|integer',
            'items.*.location' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'name' => $request->name,
            'customer_id' => $request->customer_id,
            'taxable' => $request->has('apply_sales_tax'),
        ]);

        $invoice->items()->delete();

        $total = 0;
        foreach ($request->items as $item) {
            $lineTotal = $item['quantity'] * $item['price'];

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'flavor_id' => $item['flavor_id'],
                'inventory_allocation_id' => $item['location'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $lineTotal,
            ]);

            $total += $lineTotal;
        }

        if ($request->has('apply_sales_tax')) {
            $taxRate = floatval(config('your.tax_config.sales_tax_rate', 0));
            $total += $total * ($taxRate / 100);
        }

        $invoice->update(['total_price' => $total]);

        return redirect()->route('franchise.invoice.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        $invoice->items()->delete();

        $invoice->delete();

        return redirect()->route('franchise.invoice.index')->with('success', 'Invoice deleted successfully.');
    }



    public function show($id)
    {
        $invoice = Invoice::with('items.flavor')->findOrFail($id);

        $subtotal = $invoice->items->sum(function ($item) {
            return (float) $item->total_price;
        });

        $tax = 0;
        $taxRate = 10;
        if ($invoice->taxable) {
            $tax = $subtotal * ($taxRate / 100);
        }

        $total = $subtotal + $tax;

        $customers = Customer::where('franchisee_id', Auth::user()->franchisee_id)->get();

        $allocations = InventoryAllocation::with('flavor')
            ->join('fgp_items', 'fgp_items.fgp_item_id', '=', 'inventory_allocations.fgp_item_id')
            ->select(
                'inventory_allocations.*',
                'fgp_items.name',
                'fgp_items.image1',
                'fgp_items.case_cost'
            )
            ->get();

        $franchisee = $taxRate;

        return view('franchise_admin.payment.invoice.view', compact(
            'invoice',
            'franchisee',
            'customers',
            'allocations',
            'subtotal',
            'tax',
            'total'
        ));
    }
}
