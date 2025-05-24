<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $records = $this->getTransactionForAdmin(Auth::user()->role_id);

        return view('admin.report', compact('records'));
    }

    private function getTransactionForAdmin($roleId) {
        return TransactionDetail::with('product', 'transactionHeader')
        ->whereHas('product', function ($query) use ($roleId) {
            $query->where('vocational_id', $roleId - 1);
        })->get();
    }
}
