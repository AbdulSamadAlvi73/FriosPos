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

            $orders = FpgOrder::paginate(10); // 10 items per page

        $totalOrders = $orders->count();

        return view('corporate_admin.view_orders.index', compact('orders', 'totalOrders'));
    }


    public function updateStatus(Request $request)
    {
        try {
            // Log the raw request data for debugging
            \Log::info('Update status request data:', $request->all());
            
            $validated = $request->validate([
                'status' => 'required|string|in:Pending,Paid,Shipped,Delivered',
                'date_transaction' => 'required|string',
                'id' => 'required'
            ]);
            
            // Log that validation passed
            \Log::info('Validation passed, proceeding with update');
            
            // Use a single method to update - by ID is more reliable
            $order = FpgOrder::find($request->id);
            
            if (!$order) {
                \Log::warning('Order not found with ID: ' . $request->id);
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }
            
            $order->status = $request->status;
            $order->save();
            
            \Log::info('Order updated successfully, ID: ' . $request->id);
            
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
