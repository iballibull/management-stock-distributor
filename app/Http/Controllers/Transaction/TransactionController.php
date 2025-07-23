<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function omzet()
    {
        return view('transaction.omzet');
    }

    public function revenue()
    {
        return view('transaction.revenue');
    }
}
