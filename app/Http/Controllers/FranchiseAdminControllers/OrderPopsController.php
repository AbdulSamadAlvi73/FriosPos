<?php

namespace App\Http\Controllers\FranchiseAdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\FpgItem;
use App\Models\FpgCategory;
use App\Models\FpgOrder;
class OrderPopsController extends Controller
{
    public function index()
    {
        $currentMonth = strval(Carbon::now()->format('n')); // Get current month as single-digit (1-12)
    
        // Fetch all items without filtering
        $pops = FpgItem::where('orderable', 1)
            ->with('categories') // Load related categories
            ->get();
    
        // Add an "availability" field dynamically
        foreach ($pops as $pop) {
            $pop->availability = in_array($currentMonth, json_decode($pop->dates_available, true)) 
                ? 'Available' 
                : 'Not Available';
        }
    
        // Count total available flavor pops
        $totalPops = $pops->where('availability', 'Available')->count();
    
        return view('franchise_admin.orderpops.index', compact('pops', 'totalPops'));
    }

  public function create()
  {
      $currentMonth = strval(Carbon::now()->format('n'));
  
      $pops = FpgItem::where('orderable', 1)
          ->with('categories')
          ->get();
  
      $categorizedItems = [];
      foreach ($pops as $pop) {
          $pop->availability = (in_array($currentMonth, json_decode($pop->dates_available, true)) && $pop->stock > 0) 
              ? 'Available' 
              : 'Not Available';
  
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
  
      $items = json_decode($request->input('ordered_items'), true) ?: [];
  
      if (empty($items)) {
          \Log::warning('No items received in order confirmation.');
      }
  
      return view('franchise_admin.orderpops.confirm', compact('items'));
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

