<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
    /**
     * Display a listing of the addresses.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $addresses = Auth::user()->addresses;
        
        return view('shipping.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $governorates = Governorate::active()->get();
        
        return view('shipping.create', compact('governorates'));
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'governorate_id' => 'required|exists:governorates,id',
            'city_id' => 'required|exists:cities,id',
            'street_address' => 'required|string|max:255',
            'building_number' => 'nullable|string|max:50',
            'floor_number' => 'nullable|string|max:50',
            'apartment_number' => 'nullable|string|max:50',
            'delivery_instructions' => 'nullable|string|max:500',
            'is_default' => 'boolean',
        ]);
        
        // If this is the first address or is_default is checked, set all other addresses to non-default
        if ($request->is_default || Auth::user()->addresses->count() === 0) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }
        
        // Create address
        $address = Address::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'mobile_number' => $request->mobile_number,
            'governorate_id' => $request->governorate_id,
            'city_id' => $request->city_id,
            'street_address' => $request->street_address,
            'building_number' => $request->building_number,
            'floor_number' => $request->floor_number,
            'apartment_number' => $request->apartment_number,
            'delivery_instructions' => $request->delivery_instructions,
            'is_default' => $request->is_default || Auth::user()->addresses->count() === 0,
        ]);
        
        if ($request->is_checkout) {
            // If coming from checkout, redirect back to checkout
            return redirect()->route('checkout.index')->with('success', 'Address added successfully.');
        }
        
        return redirect()->route('shipping.index')->with('success', 'Address added successfully.');
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address)
    {
        if (!Auth::check() || $address->user_id !== Auth::id()) {
            return redirect()->route('shipping.index');
        }
        
        $governorates = Governorate::active()->get();
        $cities = City::where('governorate_id', $address->governorate_id)->active()->get();
        
        return view('shipping.edit', compact('address', 'governorates', 'cities'));
    }

    /**
     * Update the specified address in storage.
     */
    public function update(Request $request, Address $address)
    {
        if (!Auth::check() || $address->user_id !== Auth::id()) {
            return redirect()->route('shipping.index');
        }
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'governorate_id' => 'required|exists:governorates,id',
            'city_id' => 'required|exists:cities,id',
            'street_address' => 'required|string|max:255',
            'building_number' => 'nullable|string|max:50',
            'floor_number' => 'nullable|string|max:50',
            'apartment_number' => 'nullable|string|max:50',
            'delivery_instructions' => 'nullable|string|max:500',
            'is_default' => 'boolean',
        ]);
        
        // If is_default is checked, set all other addresses to non-default
        if ($request->is_default) {
            Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }
        
        // Update address
        $address->update([
            'full_name' => $request->full_name,
            'mobile_number' => $request->mobile_number,
            'governorate_id' => $request->governorate_id,
            'city_id' => $request->city_id,
            'street_address' => $request->street_address,
            'building_number' => $request->building_number,
            'floor_number' => $request->floor_number,
            'apartment_number' => $request->apartment_number,
            'delivery_instructions' => $request->delivery_instructions,
            'is_default' => $request->is_default,
        ]);
        
        return redirect()->route('shipping.index')->with('success', 'Address updated successfully.');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(Address $address)
    {
        if (!Auth::check() || $address->user_id !== Auth::id()) {
            return redirect()->route('shipping.index');
        }
        
        // Check if this is the only address
        if (Address::where('user_id', Auth::id())->count() === 1) {
            return redirect()->route('shipping.index')
                ->with('error', 'You cannot delete your only address.');
        }
        
        // If this is the default address, set another address as default
        if ($address->is_default) {
            $newDefault = Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }
        
        // Delete address
        $address->delete();
        
        return redirect()->route('shipping.index')->with('success', 'Address deleted successfully.');
    }

    /**
     * Set the specified address as default.
     */
    public function setDefault(Address $address)
    {
        if (!Auth::check() || $address->user_id !== Auth::id()) {
            return redirect()->route('shipping.index');
        }
        
        // Set all addresses to non-default
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        
        // Set this address as default
        $address->update(['is_default' => true]);
        
        return redirect()->route('shipping.index')->with('success', 'Default address updated successfully.');
    }

    /**
     * Get cities for a governorate.
     */
    public function getCities(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
        ]);
        
        $cities = City::where('governorate_id', $request->governorate_id)
            ->active()
            ->get(['id', 'name_ar', 'name_en']);
        
        return response()->json($cities);
    }
}
