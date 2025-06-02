<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getAllCartItems();

        return view('customer.cart', compact('cartItems'));
    }

    private function getAllCartItems()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::user()->id)->get();

        return $cartItems;
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->productId);
        $cart = Cart::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            if ($cart->quantity > $product->stock) {
                $cart->quantity = $product->stock;
            }
            $cart->save();
        } else {
            $cart = new Cart();
            $cart->user_id = Auth::user()->id;
            $cart->product_id = $product->id;
            $cart->quantity = $request->quantity;
            $cart->save();
        }

        return redirect()->back();
    }

    public function decreaseQuantity(Request $request)
    {
        $cart = Cart::find($request->cartItemId);
        if ($cart->quantity > 1) {
            $cart->quantity -= 1;
            $cart->save();
        } else {
            $cart->delete();
        }
        return redirect()->back();
    }

    public function increaseQuantity(Request $request)
    {
        $cart = Cart::find($request->cartItemId);
        if ($cart->quantity < $cart->product->stock) {
            $cart->quantity += 1;
            $cart->save();
        }
        return redirect()->back();
    }

    public function checkout()
    {
        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kamu kosong.');
        }

        DB::beginTransaction();
        try {
            $total = 0;

            // Buat transaksi utama
            $transaction = TransactionHeader::create([
                'user_id' => $user->id,
                'status' => 'Accepted', // atau 'Pending' kalau ada step pembayaran
                'created_at' => now()
            ]);

            foreach ($cartItems as $cart) {
                $product = $cart->product;
                $subtotal = $product->price * $cart->quantity;
                $total += $subtotal;

                // Kurangi stok
                if ($product->stock < $cart->quantity) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi.");
                }
                $product->stock -= $cart->quantity;
                $product->save();

                // Detail transaksi
                TransactionDetail::create([
                    'transaction_header_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $cart->quantity,
                ]);
            }

            // Hapus keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();
            return redirect()->route('customer.cart')->with('success', 'Checkout berhasil. Total: Rp. ' . number_format($total, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }
}
