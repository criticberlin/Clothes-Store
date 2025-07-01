<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // You can fetch featured products or other data needed for the homepage
        $featuredProducts = Product::take(8)->get();
        
        return view('welcome', compact('featuredProducts'));
    }
} 