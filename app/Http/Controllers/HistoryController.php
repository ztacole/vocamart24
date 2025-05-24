<?php

namespace App\Http\Controllers;

use App\Models\TransactionHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $histories = $this->getCustomerHistories();

        return view('customer.history', compact('histories'));
    }

    private function getCustomerHistories() {
        return TransactionHeader::with('details', 'details.product')->where('user_id', Auth::user()->id)->get();
    }
}
