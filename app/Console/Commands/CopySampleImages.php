<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CopySampleImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:copy-samples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy sample images to the storage directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Copying sample images to storage...');
        
        // Create directories if they don't exist
        Storage::disk('public')->makeDirectory('products');
        Storage::disk('public')->makeDirectory('categories');
        Storage::disk('public')->makeDirectory('users');
        
        // Create public image directories if they don't exist
        if (!File::exists(public_path('images'))) {
            File::makeDirectory(public_path('images'));
        }
        if (!File::exists(public_path('images/products'))) {
            File::makeDirectory(public_path('images/products'));
        }
        if (!File::exists(public_path('images/categories'))) {
            File::makeDirectory(public_path('images/categories'));
        }
        if (!File::exists(public_path('images/users'))) {
            File::makeDirectory(public_path('images/users'));
        }
        
        // Create simple placeholder files
        $this->createTextPlaceholder(public_path('images/default-avatar.png'), 'Default Avatar');
        $this->createTextPlaceholder(public_path('images/products/default.jpg'), 'Default Product Image');
        $this->createTextPlaceholder(public_path('images/products/default-thumbnail.jpg'), 'Default Product Thumbnail');
        $this->createTextPlaceholder(public_path('images/categories/default.jpg'), 'Default Category Image');
        
        // Create product images
        $productImages = [
            'men-tshirt.jpg' => "Men's T-Shirt",
            'men-jeans.jpg' => "Men's Jeans",
            'women-dress.jpg' => "Women's Dress",
            'women-blouse.jpg' => "Women's Blouse",
            'kids-tshirt.jpg' => "Kid's T-Shirt",
        ];
        
        foreach ($productImages as $filename => $description) {
            $this->createTextPlaceholder(public_path('images/products/' . $filename), $description);
            // Also create in storage
            Storage::disk('public')->put('products/' . $filename, $description);
        }
        
        // Create category images
        $categoryImages = [
            'men.jpg' => "Men's Category",
            'women.jpg' => "Women's Category",
            'kids.jpg' => "Kids Category",
            'accessories.jpg' => "Accessories Category",
            'footwear.jpg' => "Footwear Category",
        ];
        
        foreach ($categoryImages as $filename => $description) {
            $this->createTextPlaceholder(public_path('images/categories/' . $filename), $description);
            // Also create in storage
            Storage::disk('public')->put('categories/' . $filename, $description);
        }
        
        // Copy default images to storage
        Storage::disk('public')->put('default-avatar.png', File::get(public_path('images/default-avatar.png')));
        Storage::disk('public')->put('products/default.jpg', File::get(public_path('images/products/default.jpg')));
        Storage::disk('public')->put('products/default-thumbnail.jpg', File::get(public_path('images/products/default-thumbnail.jpg')));
        Storage::disk('public')->put('categories/default.jpg', File::get(public_path('images/categories/default.jpg')));
        
        $this->info('Sample images created successfully!');
        $this->info('Remember to run "php artisan storage:link" if you haven\'t already.');
        
        return Command::SUCCESS;
    }
    
    /**
     * Create a simple text file as a placeholder
     */
    protected function createTextPlaceholder($path, $content)
    {
        if (!File::exists($path)) {
            File::put($path, $content);
            $this->info("Created placeholder file: " . basename($path));
        }
    }
} 