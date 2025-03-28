<?php

namespace App\Http\Controllers\FranchiseAdminControllers;

use Carbon\Carbon;
use App\Models\FpgItem;
use App\Models\FpgOrder;
use App\Models\FpgCategory;
use Illuminate\Http\Request;
use App\Models\AdditionalCharge;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class OrderPopsController extends Controller
{
    public function index()
    {
        $currentMonth = strval(Carbon::now()->format('n')); // Get current month as single-digit (1-12)
    
        // Fetch only items that are orderable, in stock, and available in the current month
        $pops = FpgItem::where('orderable', 1)
            ->where('internal_inventory', '>', 0) // Ensure the item is in stock
            ->get()
            ->filter(function ($pop) use ($currentMonth) {
                $availableMonths = json_decode($pop->dates_available, true);
                return in_array($currentMonth, $availableMonths ?? []);
            });
    
        // Count total available flavor pops
        $totalPops = $pops->count();
    
        return view('franchise_admin.orderpops.index', compact('pops', 'totalPops'));
    }
    
    public function create()
    {
        $currentMonth = strval(Carbon::now()->format('n'));
    
        // Fetch only orderable, in-stock, and currently available items
        $pops = FpgItem::where('orderable', 1)
            ->where('internal_inventory', '>', 0) // Ensure item is in stock
            ->get()
            ->filter(function ($pop) use ($currentMonth) {
                $availableMonths = json_decode($pop->dates_available, true);
                return in_array($currentMonth, $availableMonths ?? []);
            });
    
        $categorizedItems = [];
        foreach ($pops as $pop) {
            foreach ($pop->categories as $category) {
                $types = json_decode($category->type, true); // Decode JSON types
    
                foreach ($types as $type) {
                    $categorizedItems[$type][$category->name][] = $pop;
                }
            }
        }
    
        return view('franchise_admin.orderpops.create', compact('categorizedItems'));
    }
    
    public function confirmOrder(Request $request)
    {
        \Log::info('Received Order Data:', ['ordered_items' => $request->input('ordered_items')]);
    
        // Decode the JSON input
        $items = json_decode($request->input('ordered_items'), true) ?: [];
    
        if (empty($items)) {
            \Log::warning('No items received in order confirmation.');
        }
    
        // Fetch additional charges from the database
        $requiredCharges = AdditionalCharge::where('charge_optional', 'required')->get();
        $optionalCharges = AdditionalCharge::where('charge_optional', 'optional')->get();
    
        return view('franchise_admin.orderpops.confirm', compact('items', 'requiredCharges', 'optionalCharges'));
    }
    
  public function store(Request $request)
  {
      // Validate request
      $request->validate([
          'items' => 'required|array',
          'items.*.fgp_item_id' => 'required|exists:fpg_items,fgp_item_id',
          'items.*.user_ID' => 'required|exists:users,user_id',
          'items.*.unit_cost' => 'required|numeric|min:0',
          'items.*.unit_number' => 'required|integer|min:1',
      ]);
  
      foreach ($request->items as $item) {
          FpgOrder::create([
              'user_ID' => $item['user_ID'],
              'fgp_item_id' => $item['fgp_item_id'],
              'unit_cost' => $item['unit_cost'],
              'unit_number' => $item['unit_number'],
              'date_transaction' => now(),
              'ACH_data' => null,
              'status' => 'Pending',
          ]);
      }
  
      return redirect()->route('franchise_admin.orderpops.index')->with('success', 'Order placed successfully!');
  }
  
  
    
}

