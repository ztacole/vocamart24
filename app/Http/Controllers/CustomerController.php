<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vocational;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $vocations = $this->getVocations();
        $search = $request->query('search');
        $vocationId = $request->query('vocationId');

        $products = $this->getProducts($vocationId, $search);

        return view('customer.index', compact('vocations', 'products'));
    }

    private function getVocations() {
        return Vocational::all();
    }

    private function getProducts($vocationId = null, $search = null) {
        $products = Product::query();

        if ($vocationId) {
            $products = $products->where('vocational_id', $vocationId);
        }

        if ($search) {
            $products = $products->where('name', 'like', '%' . $search . '%');
        }

        return $products->get();
    }
}
