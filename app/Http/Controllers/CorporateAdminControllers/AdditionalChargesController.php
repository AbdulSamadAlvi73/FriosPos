<?php

namespace App\Http\Controllers\CorporateAdminControllers;

use App\Http\Controllers\Controller;
use App\Models\AdditionalCharge;
use Illuminate\Http\Request;

class AdditionalChargesController extends Controller
{
    public function index()
    {
        $additionalCharges = AdditionalCharge::all();
        $totalCharges = $additionalCharges->count();

        return view('corporate_admin.additional_charges.index', compact('additionalCharges','totalCharges'));
    }
    public function create()
    {
        return view('corporate_admin.additional_charges.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'charge_name' => 'required|string|max:255',
            'charge_price' => 'required|numeric|min:0',
            'charge_optional' => 'required|in:optional,required',
        ]);
    
        AdditionalCharge::create($request->all());
    
        return redirect()->route('corporate_admin.additionalcharges.index')
            ->with('success', 'Additional charge added successfully.');
    }
    

    public function edit(AdditionalCharge $additionalcharges)
    {
        return view('corporate_admin.additional_charges.edit', compact('additionalcharges'));
    }

    public function update(Request $request, AdditionalCharge $additionalcharges)
    {
        $request->validate([
            'charge_name' => 'required|string|max:255',
            'charge_price' => 'required|numeric|min:0',
            'charge_optional' => 'required|in:optional,required',
        ]);

        $additionalcharges->update($request->all());

        return redirect()->route('corporate_admin.additionalcharges.index')
            ->with('success', 'Additional charge updated successfully.');
    }

    public function destroy(AdditionalCharge $additionalcharges)
    {
        $additionalcharges->delete();
        return redirect()->route('corporate_admin.additionalcharges.index')
            ->with('success', 'Additional charge deleted successfully.');
    }
}

