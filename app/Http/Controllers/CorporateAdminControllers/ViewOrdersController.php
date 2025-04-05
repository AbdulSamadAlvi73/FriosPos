<?php

namespace App\Http\Controllers\CorporateAdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FpgOrder;
use App\Models\User;
use App\Models\FpgItem;

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
            'user_ID'
        )
        ->with('user') // Load user data
        ->groupBy('date_transaction', 'user_ID', 'status')
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
        $request->validate([
            'status' => 'required|in:Pending,Paid,Shipped,Delivered',
            'date_transaction' => 'required|date_format:Y-m-d H:i:s'
        ]);
    
        FpgOrder::where('date_transaction', $request->date_transaction)
                ->update(['status' => $request->status]);
    
        return response()->json(['message' => 'Order status updated successfully!']);
    }
    
    
}
