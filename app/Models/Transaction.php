<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transaksi_penjualan';
    public function get_transaction(){
        $sql = $this->select('transaksi_penjualan.*', 'products.title','products.price', 'category_product.product_category_name', DB::raw('(jumlah * price) as total_harga'))
                    ->join('products', 'products.id', "=", "transaksi_penjualan.id_products")
                    ->join('category_product', 'category_product.id', '=','products.product_category_id');
        
        return $sql;
    }
}