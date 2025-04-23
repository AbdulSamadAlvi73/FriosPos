<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\FpgItem;
use App\Models\FranchiseEvent;
use App\Models\FranchiseEventItem;
use App\Models\InventoryAllocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index() {
        // $events = FranchiseEvent::orderBy('start_date')->get();
        $today = Carbon::today()->toDateString();

        $events = FranchiseEvent::select('*')
            ->orderByRaw("CASE WHEN start_date >= ? THEN 0 ELSE 1 END", [$today])
            ->orderBy('start_date')
            ->get();
        return view('franchise_admin.event.index', compact('events'));
    }

    public function create() {
        $items = FpgItem::get();
        return view('franchise_admin.event.create', compact('items'));
    }

    public function store(Request $request) {
        $event = FranchiseEvent::create([
            'franchise_id' => Auth::id(),
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        foreach ($request->items as $key => $item) {
            FranchiseEventItem::create([
                'franchise_event_id' => $event->id,
                'item_id' => $item,
                'quantity' => $request->quantity[$key]
            ]);
        }


        return redirect()->back()->with('success','Event created successfully');
    }

    public function compare(FranchiseEvent $event) {
        $inventory = InventoryAllocation::get();
        return view('franchise_admin.event.compare', compact('event','inventory'));
        // dd($event);
    }
}
