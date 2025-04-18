<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\FpgItem;
use App\Models\FpgOrder;
use App\Models\InventoryAllocation;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $deliveredOrders = FpgOrder::where('status', 'delivered')->get();
        $shippedOrders = FpgOrder::where('status', 'shipped')->count();
        $paidOrders = FpgOrder::where('status', 'paid')->count();
        $pendingOrders = FpgOrder::where('status', 'pending')->count();
        return view('franchise_admin.inventory.index', compact('deliveredOrders', 'shippedOrders', 'pendingOrders','paidOrders'));
    }
    public function inventoryLocations()
    {
        $flavors = FpgItem::get();
        $initialPopFlavors = [];
        foreach ($flavors as $flavor) {
            $initialPopFlavors[] = [
                'name' => $flavor->name,
                'image1' => $flavor->image1,
                'available' => $flavor->availableQuantity(),
            ];
        }
        return view('franchise_admin.inventory.locations', compact('flavors', 'initialPopFlavors'));
    }
    public function allocateInventory(Request $request)
    {
        try {
            foreach ($request->allocatedInventory as $item) {
                $fpg_item_id = FpgItem::where('name', $item['flavor'])->first()->fgp_item_id ?? null;
                if (!$fpg_item_id) {
                    continue;
                }
                // return $fpg_item_id;
                InventoryAllocation::create([
                    'fpg_item_id' => $fpg_item_id,
                    'quantity' => $item['cases'],
                    'location' => $item['location']
                ]);
            }
            return response()->json([
                'error' => false,
                'message' => 'location allocated successfully'
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ]);
        }
    }
}
