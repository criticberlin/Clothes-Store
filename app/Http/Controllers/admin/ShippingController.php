<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Governorate;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
    /**
     * Display a listing of the governorates.
     */
    public function governorates()
    {
        $governorates = Governorate::orderBy('name_en')->get();
        
        return view('admin.shipping.governorates.index', compact('governorates'));
    }
    
    /**
     * Show the form for creating a new governorate.
     */
    public function createGovernorate()
    {
        return view('admin.shipping.governorates.create');
    }
    
    /**
     * Store a newly created governorate in storage.
     */
    public function storeGovernorate(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        Governorate::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.shipping.governorates')
            ->with('success', 'Governorate created successfully.');
    }
    
    /**
     * Show the form for editing the specified governorate.
     */
    public function editGovernorate(Governorate $governorate)
    {
        return view('admin.shipping.governorates.edit', compact('governorate'));
    }
    
    /**
     * Update the specified governorate in storage.
     */
    public function updateGovernorate(Request $request, Governorate $governorate)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $governorate->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'is_active' => $request->is_active ?? false,
        ]);
        
        return redirect()->route('admin.shipping.governorates')
            ->with('success', 'Governorate updated successfully.');
    }
    
    /**
     * Remove the specified governorate from storage.
     */
    public function destroyGovernorate(Governorate $governorate)
    {
        // Check if governorate has cities
        if ($governorate->cities()->count() > 0) {
            return redirect()->route('admin.shipping.governorates')
                ->with('error', 'Cannot delete governorate with cities. Delete cities first.');
        }
        
        $governorate->delete();
        
        return redirect()->route('admin.shipping.governorates')
            ->with('success', 'Governorate deleted successfully.');
    }
    
    /**
     * Display a listing of the cities.
     */
    public function cities(Request $request)
    {
        $governorateId = $request->governorate_id;
        
        $query = City::with('governorate');
        
        if ($governorateId) {
            $query->where('governorate_id', $governorateId);
        }
        
        $cities = $query->orderBy('name_en')->paginate(20);
        $governorates = Governorate::orderBy('name_en')->get();
        
        return view('admin.shipping.cities.index', compact('cities', 'governorates', 'governorateId'));
    }
    
    /**
     * Show the form for creating a new city.
     */
    public function createCity()
    {
        $governorates = Governorate::active()->orderBy('name_en')->get();
        
        return view('admin.shipping.cities.create', compact('governorates'));
    }
    
    /**
     * Store a newly created city in storage.
     */
    public function storeCity(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        City::create([
            'governorate_id' => $request->governorate_id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.shipping.cities')
            ->with('success', 'City created successfully.');
    }
    
    /**
     * Show the form for editing the specified city.
     */
    public function editCity(City $city)
    {
        $governorates = Governorate::orderBy('name_en')->get();
        
        return view('admin.shipping.cities.edit', compact('city', 'governorates'));
    }
    
    /**
     * Update the specified city in storage.
     */
    public function updateCity(Request $request, City $city)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $city->update([
            'governorate_id' => $request->governorate_id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'is_active' => $request->is_active ?? false,
        ]);
        
        return redirect()->route('admin.shipping.cities')
            ->with('success', 'City updated successfully.');
    }
    
    /**
     * Remove the specified city from storage.
     */
    public function destroyCity(City $city)
    {
        // Check if city has addresses
        if ($city->addresses()->count() > 0) {
            return redirect()->route('admin.shipping.cities')
                ->with('error', 'Cannot delete city with addresses.');
        }
        
        $city->delete();
        
        return redirect()->route('admin.shipping.cities')
            ->with('success', 'City deleted successfully.');
    }
    
    /**
     * Display a listing of the shipping methods.
     */
    public function methods()
    {
        $shippingMethods = ShippingMethod::all();
        
        return view('admin.shipping.methods.index', compact('shippingMethods'));
    }
    
    /**
     * Show the form for creating a new shipping method.
     */
    public function createMethod()
    {
        return view('admin.shipping.methods.create');
    }
    
    /**
     * Store a newly created shipping method in storage.
     */
    public function storeMethod(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_methods',
            'description' => 'nullable|string|max:500',
            'cost' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        ShippingMethod::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'cost' => $request->cost,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('admin.shipping.methods')
            ->with('success', 'Shipping method created successfully.');
    }
    
    /**
     * Show the form for editing the specified shipping method.
     */
    public function editMethod(ShippingMethod $method)
    {
        return view('admin.shipping.methods.edit', compact('method'));
    }
    
    /**
     * Update the specified shipping method in storage.
     */
    public function updateMethod(Request $request, ShippingMethod $method)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_methods,code,' . $method->id,
            'description' => 'nullable|string|max:500',
            'cost' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $method->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'cost' => $request->cost,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->is_active ?? false,
        ]);
        
        return redirect()->route('admin.shipping.methods')
            ->with('success', 'Shipping method updated successfully.');
    }
    
    /**
     * Toggle the active status of the specified shipping method.
     */
    public function toggleMethodStatus(ShippingMethod $method)
    {
        $method->update([
            'is_active' => !$method->is_active,
        ]);
        
        return redirect()->route('admin.shipping.methods')
            ->with('success', 'Shipping method status updated successfully.');
    }
    
    /**
     * Remove the specified shipping method from storage.
     */
    public function destroyMethod(ShippingMethod $method)
    {
        $method->delete();
        
        return redirect()->route('admin.shipping.methods')
            ->with('success', 'Shipping method deleted successfully.');
    }
}
