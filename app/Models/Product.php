<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    /**
    * fillable
    * 
    * @var array
    */
    protected $fillable = [
        'images',
        'title',
        'product_category_id',
        'supplier_name',
        'description',
        'price',
        'stock'
    ];
    public function get_product(){
        // get all products
        $sql = $this->select("products.*", "category_product.product_category_name as product_category_name", "supplier.supplier_name as supplier_name")
                    ->join("category_product", "category_product.id", "=", "products.product_category_id")
                    ->join("supplier", "products.supplier_name", "=", "supplier.id");

        return $sql;
    }
    public function get_category_product(){
        $sql = DB::table('category_product')->select('*');

        return $sql;
    }

    public $timestaps = true;

    // Tambahkan metode untuk menyimpan data
    public static function storeProduct($request, $image)
    {
        // Simpan produk baru menggunakan mass assignment
        return self::create([
            'images'                => $image->hashName(),
            'title'                 => $request->title,
            'product_category_id'   => $request->product_category_id,
            'supplier_name'         => $request->supplier_name,
            'description'           => $request->description,
            'price'                 => $request->price,
            'stock'                 => $request->stock
        ]);
    }
}