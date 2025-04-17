<?php

namespace App\Http\Controllers\CorporateAdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FpgOrder;
use App\Models\User;
use App\Models\FpgItem;
use Carbon\Carbon;

class ViewOrdersController extends Controller
{
    public function index()
    {
        // Fetch orders grouped by date_transaction
        $orders = FpgOrder::select(
            'date_transaction',
            \DB::raw('SUM(unit_cost * unit_number) as total_price'),
            \DB::raw('SUM(unit_number) as total_quantity'),
            'status',
            'user_ID',
            'fgp_ordersID'
        )
            ->with('user') // Load user data
            ->groupBy('date_transaction', 'user_ID', 'status','fgp_ordersID')
            ->orderBy('date_transaction', 'desc')
            ->get()
            ->map(function ($order) {
                // Format the date
                $order->date_transaction = \Carbon\Carbon::parse($order->date_transaction)->format('M d, Y h:i A');
                return $order;
            });

        $totalOrders = $orders->count();

        return view('corporate_admin.view_orders.index', compact('orders', 'totalOrders'));
    }


    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:Pending,Paid,Shipped,Delivered',
                'date_transaction' => 'required'
            ]);
            $formattedDate = Carbon::parse($request->date_transaction)->format('Y-m-d H:i:s');

            FpgOrder::where('date_transaction', $formattedDate)
                ->update(['status' => $request->status]);

                
            FpgOrder::find($request->id)->update(['status' => $request->status]);

            return response()->json(['message' => 'Order status updated successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function edit($orderId) {
        $order = FpgOrder::find($orderId);
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
        return view('corporate_admin.view_orders.edit', compact('order','categorizedItems'));
    }
}
