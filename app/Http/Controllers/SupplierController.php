<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\View\View;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar supplier
     *
     * @return View
     */
    public function index() : View
    {
        // Ambil semua supplier
        $suppliers = Supplier::latest()->paginate(10);

        // Render view dengan data supplier
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Menghapus supplier
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Temukan supplier berdasarkan id, atau gagal jika tidak ada
        $supplier = Supplier::findOrFail($id);

        // Hapus supplier
        $supplier->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}