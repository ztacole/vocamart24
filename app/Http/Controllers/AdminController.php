<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $income = $this->getIncome($user->id) ?? 0;
        $productCount = $this->getProductCount($user->id) ?? 0;

        return view('admin.index', compact('income', 'productCount'));
    }

    private function getIncome($userId)
    {
        $transactionRecords = TransactionDetail::with('product')
        ->whereHas('product', function ($query) use ($userId) {
            $query->where('vocational_id', $userId - 1);
        })->get();

        $totalPrice = $transactionRecords->sum( function ($record) {
            return $record->product->price * $record->quantity;
        });

        return $totalPrice;
    }

    private function getProductCount($userId)
    {
        return Product::where('vocational_id', $userId - 1)->count();
    }
}
