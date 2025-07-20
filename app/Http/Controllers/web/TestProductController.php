<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestProductController extends Controller 
{
    public function index()
    {
        $products = Product::paginate(12);
        return view('products.list', compact('products'));
    }
} 