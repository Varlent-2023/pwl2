<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\View\View;
use Illuminate\Http\Request;

class TransaksiPenjualanController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index():view
    {
        $transaction = new Transaction;
        $transaction = $transaction->get_transaction()
                            -> latest()
                            ->paginate(10);
        
        return view('transaction.index', compact('transaction'));
    }
}