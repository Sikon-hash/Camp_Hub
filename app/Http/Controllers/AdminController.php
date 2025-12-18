<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Services\BlockchainValidator; // Pastikan Service ini terpanggil

class AdminController extends Controller
{
    // ==========================================
    // SYSTEM HEALTH (BLOCKCHAIN FORENSICS)
    // ==========================================
    public function system_health(BlockchainValidator $validator)
    {
        // Memanggil logika validasi rantai blok
        $healthStatus = $validator->validateChain();

        return view('admin.system_health', compact('healthStatus'));
    }

    // ==========================================
    // CATEGORY MANAGEMENT
    // ==========================================
    public function view_category()
    {
        $data = Category::all();
        return view('admin.category', compact('data'));
    }

    public function add_category(Request $request)
    {
        $category = new Category;
        $category->category_name = $request->category;
        $category->save();

        toastr()->addInfo('Category Added Successfully');
        return redirect()->back();
    }

    public function delete_category($id)
    {
        $data = Category::find($id);
        $data->delete();
        return redirect()->back();
    }

    public function edit_category($id)
    {
        $data = Category::find($id);
        return view('admin.edit_category', compact('data'));
    }

    public function update_category(Request $request, $id)
    {
        $data = Category::find($id);
        $data->category_name = $request->category;
        $data->save();
        return redirect('/view_category');
    }

    // ==========================================
    // PRODUCT MANAGEMENT
    // ==========================================
    public function add_product()
    {
        $category = Category::all();
        return view('admin.add_product', compact('category'));
    }

    public function upload_product(Request $request)
    {
        $data = new Product;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->quantity = $request->qty;
        $data->category = $request->category;

        $image = $request->image;
        if($image)
        {
            $imagename = time().'.'.$image->getClientOriginalExtension();
            $request->image->move('products', $imagename);
            $data->image = $imagename;
        }

        $data->save();
        // Observer akan otomatis mencatat Blok baru di sini

        toastr()->addSuccess('Product Uploaded Successfully');
        return redirect()->back();
    }

    public function view_product()
    {
        // PERBAIKAN: Gunakan nama variabel $products (Plural)
        // Ubah paginate(2) jadi 10 agar tampilan lebih wajar
        $products = Product::paginate(10); 
        return view('admin.view_product', compact('products'));
    }

    public function update_product($id)
    {
        $data = Product::find($id);
        $category = Category::all();
        return view('admin.update_page', compact('data', 'category'));
    }

    public function edit_product(Request $request, $id)
    {
        $data = Product::find($id);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->quantity = $request->quantity;
        $data->category = $request->category;

        $image = $request->image;
        if($image)
        {
            $imagename = time().'.'.$image->getClientOriginalExtension();
            $request->image->move('products', $imagename);
            $data->image = $imagename;
        }

        $data->save();
        return redirect('/view_product');
    }

    public function product_search(Request $request)
    {
        $search = $request->search;
        
        // PERBAIKAN: Gunakan $products
        $products = Product::where('title', 'LIKE', '%'.$search.'%')
            ->orWhere('category', 'LIKE', '%'.$search.'%')
            ->paginate(10);

        return view('admin.view_product', compact('products'));
    }

    // ==========================================
    // SOFT DELETE SYSTEM (Trash & Restore)
    // ==========================================
    
    // Soft Delete (Masuk Tong Sampah)
    public function delete_product($id)
    {
        $data = Product::find($id);
        
        // PERBAIKAN: Jangan unlink gambar di sini. 
        // Biarkan gambar tetap ada kalau user mau restore.
        
        $data->delete(); // Hanya set deleted_at
        return redirect()->back();
    }

    // Restore (Kembalikan dari Tong Sampah)
    public function restore_product($id)
    {
        $data = Product::withTrashed()->find($id);
        $data->restore();
        return redirect()->back()->with('message', 'Product restored successfully!');
    }

    // Force Delete (Hapus Permanen)
    public function force_delete_product($id)
    {
        $data = Product::withTrashed()->find($id);

        // PERBAIKAN: Hapus gambar fisik hanya saat Force Delete
        $image_path = public_path('products/'.$data->image);
        if(file_exists($image_path)) {
            unlink($image_path);
        }

        $data->forceDelete();
        return redirect()->back()->with('message', 'Product permanently deleted!');
    }

    public function trashed_products()
    {
        $products = Product::onlyTrashed()->paginate(10);
        return view('admin.trashed_products', compact('products'));
    }

    // ==========================================
    // ORDER MANAGEMENT
    // ==========================================
    public function view_order()
    {
        $data = Order::all();
        return view('admin.order', compact('data'));
    }

    public function on_the_way($id)
    {
        $data = Order::find($id);
        $data->status = 'OTW KANG';
        $data->save();
        return redirect('/view_orders');
    }

    public function delivered($id)
    {
        $data = Order::find($id);
        $data->status = 'Delivered';
        $data->save();
        return redirect('/view_orders');
    }
}