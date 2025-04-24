<?php

namespace App\Http\Controllers\CorporateAdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FpgOrder;
use App\Models\User;
use App\Models\FpgItem;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ViewOrdersController extends Controller
{
    public function index()
{
    $deliveredOrders = FpgOrder::where('status', 'delivered')->get();
    $shippedOrders = FpgOrder::where('status', 'shipped')->count();
    $paidOrders = FpgOrder::where('status', 'paid')->count();
    $pendingOrders = FpgOrder::where('status', 'pending')->count();

    $orders = FpgOrder::select(
            'user_ID',
            'date_transaction',
            \DB::raw('SUM(unit_number) as total_quantity'),
            \DB::raw('SUM(unit_number * unit_cost) as total_amount'),
            'status'
        )
        ->groupBy('date_transaction', 'user_ID', 'status')
        ->orderBy('date_transaction', 'desc')
        ->with('user')
        ->paginate(10)
        ->through(function ($order) {
            $order->date_transaction = Carbon::parse($order->date_transaction);
            return $order;
        });

    $totalOrders = $orders->total();

    return view('corporate_admin.view_orders.index', compact('deliveredOrders', 'shippedOrders', 'pendingOrders', 'paidOrders', 'orders', 'totalOrders'));
}
public function updateStatus(Request $request)
{
    try {
        // Log the raw request data for debugging
        \Log::info('Update status request data:', $request->all());

        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Paid,Shipped,Delivered',
            'date_transaction' => 'required|string',
            'fgp_ordersID' => 'required|exists:fgp_orders,fgp_ordersID' // Validate fgp_ordersID
        ]);

        // Log that validation passed
        \Log::info('Validation passed, proceeding with update');

        // Query by fgp_ordersID
        $order = FpgOrder::where('fgp_ordersID', $request->fgp_ordersID)->firstOrFail();

        $order->status = $request->status;
        $order->save();

        \Log::info('Order updated successfully, fgp_ordersID: ' . $request->fgp_ordersID);

        return response()->json([
            'message' => 'Order status updated successfully!'
        ]);
    } catch (ValidationException $e) {
        // Log validation errors
        \Log::error('Validation failed:', $e->errors());

        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        // Log other errors
        \Log::error('Exception in updateStatus: ' . $e->getMessage());

        return response()->json([
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function edit($orderId)
    {
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
        return view('corporate_admin.view_orders.edit', compact('order', 'categorizedItems'));
    }
}
