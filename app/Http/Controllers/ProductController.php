<?php

namespace App\Http\Controllers;

// Import model product
use App\Models\Product;

use App\Models\Supplier;

// import return type view
use Illuminate\View\View;

use Illuminate\Http\Request;

// import return type redirectReponse
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index() : View
    {
        // //get all products
        // $products = Product::select("products.*", "category_product.product_category_name as product_category_name")
        //                     ->join("category_product", "category_product.id", "=", "products.product_category_id")
        //                     ->latest()
        //                     ->paginate(10);

        $product = new Product;
        $products = $product->get_product()
                           ->latest()
                           ->paginate(10);

        // render view with product
        return view('products.index', compact('products'));
    }

    /**
     * create
     * 
     * @return View
     */ 
    public function create(): View
    {
        $product = new Product;
        $supplier = new Supplier;

        $data['categories'] = $product->get_category_product()->get();
        $data['suppliers'] = $supplier->get_supplier()->get();

        return view('products.create', compact('data'));
    }



    /**
     * show 
     *
     * @param mixed $id
     * @return View
     */
    public function show(string $id): View
    {
        //get product by ID
        $product_model = new Product;
        $product = $product_model->get_product()->where("products.id", $id)->firstOrFail();

        //render view with product
        return view('products.show', compact('product'));
    }

    /**
     * edit
     * 
     * @param mixed $id
     * @return View
     */ 
    public function edit(string $id): View
    {
        //get product by ID
        $product_model = new Product;
        $data['product'] = $product_model->get_product()->where("products.id", $id)->firstOrFail();

        $supplier_model = new Supplier;

        $data['categories'] = $product_model->get_category_product()->get();
        $data['suppliers'] = $supplier_model->get_supplier()->get();

        //render view with product
        return view('products.edit', compact('data'));
    }

    /**
     * destroy
     * 
     * @param mixed $id
     * @return RedirectResponse 
     */
    public function destroy($id): RedirectResponse
    {
        //get product by ID
        $product_model = new Product;
        $product = $product_model->get_product()->where("products.id", $id)->firstOrFail();

        //delete image
        Storage::delete('public/images/'.$product->image);

        //delete product
        $product->delete();

        //redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    /**
     * update
     * 
     * @param mixed $request
     * @param mixed $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        //get product by ID
        $product_model = new Product;
        $product = $product_model->get_product()->where("products.id", $id)->firstOrFail();
        //check if image is uploaded
        if ($request->hasFile('image')) {
            
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/images', $image->hashName());

            //delete old image
            Storage::delete('public/images/'.$product->image);

            //update product with new image
            $product->update([
                'images'                 => $image->hashName(),
                'title'                 => $request->title,
                'product_category_id'   => $request->product_category_id,
                'supplier_name'         => $request->supplier_name,
                'description'           => $request->description,
                'price'                 => $request->price,
                'stock'                 => $request->stock
            ]);

        } else {

            //update product without image
            $product->update([
                'title'                 => $request->title,
                'product_category_id'   => $request->product_category_id,
                'supplier_name'         => $request->supplier_name,
                'description'           => $request->description,
                'price'                 => $request->price,
                'stock'                 => $request->stock
            ]);
        }

        //redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * store
     * 
     * @param mixed $request
     * @return RedirectResponse
     */ 
    public function store(Request $request): RedirectResponse
    {
        // validate form
        // var_dump($request);exit;
        $validatedData = $request->validate([
            'image'                 => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'                 => 'required|min:5',
            'product_category_id'   => 'required|integer',
            'supplier_name'         => 'required|min:1',
            'description'           => 'required|min:10',
            'price'                 => 'required|numeric',
            'stock'                 => 'required|numeric'
        ]);

        //  Menghandle upload file gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->store('public/images'); // Simpan gambar ke folder penyimpanan

            // create product
            Product::create([
                'images'                => $image->hashName(),
                'title'                 => $request->title,
                'product_category_id'   => $request->product_category_id,
                'supplier_name'         => $request->supplier_name,
                'description'           => $request->description,
                'price'                 => $request->price,
                'stock'                 => $request->stock
            ]);

            // Redirect to the index route with a success message
            return redirect()->route(route: 'products.index')->with(key: ['success' => 'Data Berhasil Disimpan!']);
        }

        // Also redirect to index even when failed
        return redirect()->route(route: 'products.index')->with(key: ['error'=>'Data Berhasil Disimpan!']);
    }
}
