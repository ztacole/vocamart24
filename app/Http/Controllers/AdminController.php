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
        $vocationalId = $user->id - 1;

        $income = $this->getIncome($user->id) ?? 0;
        $productCount = $this->getProductCount($user->id) ?? 0;
        $monthlySales = $this->getMonthlySales($vocationalId);
        $topProducts = $this->getTopProducts($vocationalId, 5); // Ambil 5 produk terlaris

        return view('admin.index', compact('income', 'productCount', 'monthlySales', 'topProducts'));
    }

    public function getTransactionsByDate(Request $request)
    {
        $date = $request->input('date');
        $user = Auth::user();
        $vocationalId = $user->id - 1;

        $transactions = TransactionDetail::with(['product', 'transactionHeader'])
            ->whereHas('product', function ($query) use ($vocationalId) {
                $query->where('vocational_id', $vocationalId);
            })
            ->whereHas('transactionHeader', function ($query) use ($date) {
                $query->whereDate('created_at', $date)
                    ->where('status', 'Accepted');
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->transactionHeader->id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'total' => $item->product->price * $item->quantity
                ];
            });

        return response()->json($transactions);
    }

    private function getTopProducts($vocationalId, $limit = 5)
    {
        return TransactionDetail::selectRaw('products.name, SUM(transaction_details.quantity) as total_sold')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->join('transaction_headers', 'transaction_headers.id', '=', 'transaction_details.transaction_header_id')
            ->where('products.vocational_id', $vocationalId)
            ->where('transaction_headers.status', 'Accepted')
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    private function getIncome($userId)
    {
        $transactionRecords = TransactionDetail::with('product')
            ->whereHas('product', function ($query) use ($userId) {
                $query->where('vocational_id', $userId - 1);
            })->get();

        return $transactionRecords->sum(function ($record) {
            return $record->product->price * $record->quantity;
        });
    }

    private function getProductCount($userId)
    {
        return Product::where('vocational_id', $userId - 1)->count();
    }

    private function getMonthlySales($vocationalId)
    {
        $currentYear = date('Y');
        $monthlyData = [];

        // Initialize all months with 0
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = 0;
        }

        // Get actual transaction data
        $transactions = TransactionDetail::with(['product', 'transactionHeader'])
            ->whereHas('product', function ($query) use ($vocationalId) {
                $query->where('vocational_id', $vocationalId);
            })
            ->whereHas('transactionHeader', function ($query) use ($currentYear) {
                $query->where('status', 'Accepted')
                    ->whereYear('created_at', $currentYear);
            })
            ->get()
            ->groupBy(function ($item) {
                return date('n', strtotime($item->transactionHeader->created_at));
            });

        // Sum the transactions by month
        foreach ($transactions as $month => $items) {
            $monthlyData[$month] = $items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
        }

        return array_values($monthlyData); // Return just the values for the chart
    }
}
