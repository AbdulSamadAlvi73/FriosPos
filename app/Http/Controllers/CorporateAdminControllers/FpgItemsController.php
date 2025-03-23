<?php

namespace App\Http\Controllers\CorporateAdminControllers;

use App\Models\FpgItem;
use App\Models\FpgCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FpgItemsController extends Controller
{
    public function index()
    {
        $items = FpgItem::with('category')->get();
        $totalItems = $items->count();
        return view('corporate_admin.fpg_items.index', compact('items','totalItems'));
    }

    public function create()
    {
        $categories = FpgCategory::all();
        return view('corporate_admin.fpg_items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Convert dates_available from a string to an array if it's not already an array
        if ($request->has('dates_available') && is_string($request->dates_available)) {
            $request->merge([
                'dates_available' => explode(',', $request->dates_available)
            ]);
        }
        // dd($request)->all();
        $validated = $request->validate([
            'category_ID' => 'required|exists:fpg_categories,category_ID',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'case_cost' => 'required|numeric',
            'internal_inventory' => 'required|integer',
            'orderable' => 'required|boolean',
            'dates_available' => 'nullable|array',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
        
        // Check if validation passes
        // if ($validated) {
        //     return response()->json(['success' => true, 'data' => $validated]);
        // }
        
    
        // Convert multiple dates to JSON before saving
        if (!empty($validated['dates_available'])) {
            $validated['dates_available'] = json_encode($validated['dates_available']);
        }
    
        // Handle images
        if ($request->hasFile('image1')) {
            $validated['image1'] = $request->file('image1')->store('images/fpg_items', 'public');
        }
        if ($request->hasFile('image2')) {
            $validated['image2'] = $request->file('image2')->store('images/fpg_items', 'public');
        }
        if ($request->hasFile('image3')) {
            $validated['image3'] = $request->file('image3')->store('images/fpg_items', 'public');
        }
    
        // Create the item
        $item = FpgItem::create($validated);
        // $item->category()->sync([$validated['category_ID']]);
        $item->category_id = $validated['category_ID'];
    
        return redirect()->route('corporate_admin.fpgitem.index')->with('success', 'FPG Item added successfully.');
    }
    
    
    
    public function edit(FpgItem $fpgitem)
    {
        $categories = FpgCategory::all();
        return view('corporate_admin.fpg_items.edit', compact('fpgitem', 'categories'));
    }
    public function update(Request $request, FpgItem $fpgitem)
    {
        // Convert dates_available from a string to an array if it's not already an array
        if ($request->has('dates_available') && is_string($request->dates_available)) {
            $request->merge([
                'dates_available' => explode(',', $request->dates_available)
            ]);
        }
    
        $validated = $request->validate([
            'category_ID' => 'required|exists:fpg_categories,category_ID',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'case_cost' => 'required|numeric',
            'internal_inventory' => 'required|integer',
            'orderable' => 'required|boolean',
            'dates_available' => 'nullable|array',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
    
        // Convert multiple dates to JSON before saving
        if (!empty($validated['dates_available'])) {
            $validated['dates_available'] = json_encode($validated['dates_available']);
        }
    
        // Handle images
        if ($request->hasFile('image1')) {
            $validated['image1'] = $request->file('image1')->store('images/fpg_items', 'public');
        }
        if ($request->hasFile('image2')) {
            $validated['image2'] = $request->file('image2')->store('images/fpg_items', 'public');
        }
        if ($request->hasFile('image3')) {
            $validated['image3'] = $request->file('image3')->store('images/fpg_items', 'public');
        }
    
        // Update the item
        $fpgitem->update($validated);
        
        $fpgitem->category_id = $validated['category_ID'];

    
        return redirect()->route('corporate_admin.fpgitem.index')->with('success', 'FPG Item updated successfully.');
    }
    

    public function destroy(FpgItem $fpgitem)
    {
        try {
            // Detach categories to prevent foreign key constraint issues
            // if ($fpgitem->category()->exists()) {
            //     $fpgitem->category()->detach();
            // }
    
            // Delete associated images if they exist
            if ($fpgitem->image1) {
                Storage::disk('public')->delete($fpgitem->image1);
            }
            if ($fpgitem->image2) {
                Storage::disk('public')->delete($fpgitem->image2);
            }
            if ($fpgitem->image3) {
                Storage::disk('public')->delete($fpgitem->image3);
            }
    
            // Delete the item
            $fpgitem->delete();
    
            return redirect()->route('corporate_admin.fpgitem.index')->with('success', 'FPG Item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('corporate_admin.fpgitem.index')->with('error', 'Failed to delete FPG Item.');
        }
    }
    public function updateOrderable(Request $request)
    {
        try {
            $item = FpgItem::findOrFail($request->id);
            $item->orderable = $request->orderable;
            $item->save();

            return response()->json(['success' => true, 'message' => 'Orderable status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update orderable status.']);
        }
    }

}
