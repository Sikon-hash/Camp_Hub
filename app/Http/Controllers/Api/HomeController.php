<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Order::latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar Menu berhasil diambil',
            'data' => $data
        ], 200);
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        // Membuat order baru
        $order = Order::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Order berhasil dibuat',
            'data' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
            'customer_name' => 'sometimes|required|string|max:255',
            'product_id' => 'sometimes|required|integer|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:1',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        // Temukan order yang ingin diperbarui
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        // Perbarui order dengan data baru
        $order->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Order berhasil diperbarui',
            'data' => $order
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan order yang ingin dihapus
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        // Hapus order
        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Order berhasil dihapus'
        ], 200);
    }
}
