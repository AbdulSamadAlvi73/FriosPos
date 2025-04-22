<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\FpgItem;
use App\Models\FpgOrder;
use App\Models\InventoryAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function index()
    {
        $deliveredOrders = FpgOrder::where('status', 'delivered')->get();
        $shippedOrders = FpgOrder::where('status', 'shipped')->count();
        $paidOrders = FpgOrder::where('status', 'paid')->count();
        $pendingOrders = FpgOrder::where('status', 'pending')->count();


        $orders = FpgOrder::where('user_ID', Auth::id())
        ->select(
            'user_ID',
            'date_transaction',
            \DB::raw('SUM(unit_number) as total_quantity'),
            \DB::raw('SUM(unit_number * unit_cost) as total_amount'),
            'status'
        )
        ->groupBy('date_transaction', 'user_ID', 'status')
        ->orderBy('date_transaction', 'desc')
        ->with('user') // Eager load user information
        ->get()
        ->map(function ($order) {
            $order->date_transaction = Carbon::parse($order->date_transaction);
            return $order;
        });

    $totalOrders = $orders->count();


        return view('franchise_admin.inventory.index', compact('deliveredOrders', 'shippedOrders', 'pendingOrders','paidOrders', 'orders', 'totalOrders'));
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
