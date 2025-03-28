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
        // Fetch orders with franchise (user), items, and related data
        $orders = FpgOrder::with(['user', 'item'])->orderBy('date_transaction', 'desc')->get();
        $totalOrders = $orders->count();
        return view('corporate_admin.view_orders.index', compact('orders','totalOrders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = FpgOrder::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully!']);
    }
}
