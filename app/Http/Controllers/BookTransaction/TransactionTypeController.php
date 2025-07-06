<?php

namespace App\Http\Controllers\BookTransaction;

use App\Models\BookTransaction\TransactionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionTypeController extends Controller
{
    public function index()
    {
        $transactionTypes = TransactionType::get();

        return view('book-transaction.transaction-type', compact('transactionTypes'));
    }
}
