<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Models\ExpenseSubCategory;
use App\Models\Expense;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\ExpenseTransaction;
use Auth;

class ExpenseController extends Controller
{
    public function index() {
        $data['expenses'] = Expense::where('franchisee_id' , Auth::user()->franchisee_id)->get();
        $data['expenseCount'] = Expense::where('franchisee_id' , Auth::user()->franchisee_id)->count();
        return view('franchise_admin.expense.index' ,$data);
    }

    public function create() {
        $data['ExpenseCategories'] = ExpenseCategory::get();
        return view('franchise_admin.expense.create' ,$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'name' => 'required|string|max:191',
            'amount' => 'required|numeric|min:0.5',
            'date' => 'required|date',
            'stripeToken' => 'required',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $amountInCents = $request->amount * 100;

            $charge = Charge::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'description' => 'Expense Payment for: ' . $request->name,
                'source' => $request->stripeToken,
                'metadata' => [
                    'franchise_id' => Auth::user()->franchisee_id,
                    'category_id' => $request->category_id,
                    'sub_category_id' => $request->sub_category_id,
                ],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

        $expense = Expense::create([
            'franchisee_id' => Auth::user()->franchisee_id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        ExpenseTransaction::create([
            'franchisee_id' => Auth::user()->franchisee_id,
            'expense_id' => $expense->id,
            'cardholder_name' => $request->cardholder_name,
            'amount' => $request->amount,
            'stripe_payment_intent_id' => $charge->id,
            'stripe_payment_method' => $charge->payment_method ?? null,
            'stripe_currency' => $charge->currency,
            'stripe_client_secret' => $charge->client_secret ?? null,
            'stripe_status' => $charge->status,
        ]);

        return redirect()->route('franchise.expense')->with('success', 'Expense created and payment successful!');
    }


    public function edit($id) {
        $data['expense'] = Expense::where('id' , $id)->first();
        $data['ExpenseCategories'] = ExpenseCategory::get();
        return view('franchise_admin.expense.edit' ,$data);
    }

    public function update(Request $request , $id){
        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'string|max:191',
            'amount' => 'required|numeric',
            'date' => 'required',
        ]);

        $expense = Expense::where('id',$id)->update([
            'franchisee_id' => Auth::user()->franchisee_id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);


        return redirect()->route('franchise.expense')->with('success' , 'Expense updated successfully');
    }

    public function getSubCategories($category_id)
    {
        $subCategories = ExpenseSubCategory::where('category_id', $category_id)->get();

        return response()->json([
            'data' => $subCategories,
        ]);
    }

    public function delete($id){
        $expense = Expense::where('id',$id)->delete();

        return redirect()->route('franchise.expense')->with('success' , 'Expense deleted successfully');


    }
}
