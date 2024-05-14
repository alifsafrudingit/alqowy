<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeTransactionRequest;
use App\Models\SubscribeTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SubscribeTransactionController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $transactions = SubscribeTransaction::with(['user'])->orderByDesc('id')->get();

    return view('admin.transactions.index', compact('transactions'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(SubscribeTransaction $subscribeTransaction)
  {
    return view('admin.transactions.show', compact('subscribeTransaction'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(SubscribeTransaction $subscribeTransaction)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, SubscribeTransaction $subscribeTransaction)
  {
    // $data = $request->all();

    // if ($request->hasFile('proof')) {
    //   $data['proof'] = $request->file('proof')->store('proofs', 'public');
    // } else {
    //   return redirect()->route('admin.subscribe_transactions.index')->with('status', 'Bukti pembayaran belum ada');
    // }

    // $data['is_paid'] = 1;

    // $subscribeTransaction->update($data);

    // return redirect()->route('admin.subscribe_transactions.index');

    $subscribeTransaction->update([
      'is_paid' => true,
      'subscription_start_date' => Carbon::now()
    ]);

    toast('Transaction ' . $subscribeTransaction->user['name'] . ' has been approved!', 'success');

    return redirect()->route('admin.subscribe_transactions.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(SubscribeTransaction $subscribeTransaction)
  {
    //
  }
}
