<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

use App\Models\User;

use App\Models\Cart;

use App\Models\Order;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = User::where('usertype','user')->get()->count();
        $product = Product::all()->count();
        $order = Order::all()->count();
        $delivered = Order::where('status','Delivered')->get()->count();
        return view('admin.index', compact('user','product', 'order','delivered'));
    }

    public function home()
    {
        $product = Product::all();

        if(Auth::id())
        {
            
        $user = Auth::user();

        $userid =$user->id;

        $count = Cart::where('user_id', $userid)->count();

        }

        else
        {
            $count = '';
        }


        return view('home.index', compact('product', 'count'));
    }

    public function login_home()
    {
        $product = Product::all();

        if(Auth::id())
        {
            
        $user = Auth::user();

        $userid =$user->id;

        $count = Cart::where('user_id', $userid)->count();

        }

        else
        {
            $count = '';
        }

        return view('home.index', compact('product', 'count'));
    }

    public function product_details($id)
    {
        $data = Product::find($id);

        if(Auth::id())
        {
            
        $user = Auth::user();

        $userid =$user->id;

        $count = Cart::where('user_id', $userid)->count();

        }

        else
        {
            $count = '';
        }

        return view('home.product_details', compact('data', 'count'));
    }

    public function add_cart($id)
    {
        $product_id = $id;

        $user = Auth::user();

        $user_id = $user->id;

        $data = new Cart;

        $data->user_id = $user_id;

        $data->product_id = $product_id;

        $data->save();

        return redirect()->back();
    }

    public function mycart()
    {
        if(Auth::id())
        {
            $user = Auth::user();
            $userid = $user->id;
            $count = Cart::where('user_id',$userid)->count();

            $cart = Cart::where('user_id', $userid)->get();
        }
        return view('home.mycart', compact('count', 'cart'));
    }

    public function delete_cart($id)
    {
        $data = Cart::find($id);



        $data->delete();

        return redirect()->back();
    }

    public function confirm_order(Request $request)
{
    $name = $request->name;
    $address = $request->address;
    $phone = $request->phone;
    $userid = Auth::user()->id;

    // Ambil data keranjang beserta informasi produknya
    $cart = Cart::where('user_id', $userid)->get();

    foreach ($cart as $carts) {
        $order = new Order;

        $order->name = $name;
        $order->rec_address = $address;
        $order->phone = $phone;
        $order->user_id = $userid;
        $order->product_id = $carts->product_id;
        
        // 1. Ambil Quantity (default ke 1 jika kosong)
        $qty = $carts->quantity ?? 1; 
        $order->quantity = $qty;

        // 2. Hitung Total Amount (Harga Produk x Jumlah)
        // Pastikan di tabel 'products' kamu ada kolom 'price'
        if ($carts->product && $carts->product->price) {
            $order->total_amount = $carts->product->price * $qty;
        } else {
            // Jika harga tidak ditemukan, set 0 atau sesuaikan logika bisnis kamu
            $order->total_amount = 0; 
        }

        $order->save();    
        
        }

        $cart_remove = Cart::where('user_id', $userid)->get();

        foreach($cart_remove as $remove)
        {
            $data = Cart::find($remove->id);
            $data->delete();
        }
        return redirect()->back();
    }
    

}