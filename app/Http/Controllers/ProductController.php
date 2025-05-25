<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $roleId = Auth::user()->role_id;
        $search = $request->query('search');

        $products = $this->getProductForAdmin($roleId, $search);

        return view('admin.product', compact('products'));
    }

    private function getProductForAdmin($roleId, $search = null)
    {
        $products = Product::where('vocational_id', $roleId - 1);

        if ($search) {
            $products = $products->where('name', 'like', '%' . $search . '%');
        }

        return $products->get();
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        $product->delete();
        return redirect()->back();
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'stock' => 'required|integer',
                'price' => 'required|integer',
                'image' => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('public/images/products');
        }

        Product::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'image' => $filename,
            'vocational_id' => Auth::user()->role_id - 1
        ]);

        return redirect()->route('admin.product')->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'stock' => 'required|integer',
                'price' => 'required|integer',
                'image' => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->stock = $request->stock;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('public/images/products');
            $product->image = $filename;
        }

        $product->save();

        return redirect()->route('admin.product')->with('success', 'Produk berhasil diperbarui!');
    }
}
