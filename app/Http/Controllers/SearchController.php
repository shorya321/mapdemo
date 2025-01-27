<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index()
    {
        
        return Inertia::render('Home');
    }


    public function show(Vehicle $vehicle)
{
    return Inertia::render('VehicleDetail', [
        'vehicle' => $vehicle
    ]);
}

    // public function search(Request $request)
    // {
          
    //     $validated = $request->validate([
    //         'date_from' => 'required|date',
    //         'date_to' => 'required|date|after:date_from',
    //         'where' => 'nullable|string',
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'radius' => 'required|numeric',
    //     ]);
      
    //     $query = Vehicle::query()
    //         ->where('is_available', true)
    //         ->whereRaw(
    //             '(
    //                 6371 * acos(
    //                     cos(radians(?)) * cos(radians(latitude)) *
    //                     cos(radians(longitude) - radians(?)) +
    //                     sin(radians(?)) * sin(radians(latitude))
    //                 )
    //             ) <= ?',
    //             [
    //                 $validated['latitude'],
    //                 $validated['longitude'],
    //                 $validated['latitude'],
    //                 $validated['radius'] / 1000, // Convert to kilometers
    //             ]
    //         );

        
    //         // Apply location search if 'where' parameter is provided
    //     if (!empty($validated['where'])) {
    //         $locationParts = array_map('trim', explode(',', $validated['where']));
    //         $query->where(function ($q) use ($locationParts) {
    //             foreach ($locationParts as $part) {
    //                 $searchTerm = '%' . trim($part) . '%';
    //                 $q->orWhere('country', 'like', $searchTerm)
    //                 ->orWhere('state', 'like', $searchTerm)
    //                 ->orWhere('city', 'like', $searchTerm)
    //                 ->orWhere('address', 'like', $searchTerm);
    //             }
    //         });
    //     }    
      
    //     $vehicles = $query->paginate(12);
    //     echo "<pre>";
    //     print_r($vehicles);
    //     return Inertia::render('SearchResults', [
    //         'vehicles' => $vehicles,
    //         'filters' => $validated,
    //     ]);
    // }

//     public function search(Request $request)
// {
//     // print_r($request->all());
//     // die('tes');
//     $validated = $request->validate([
//         'date_from' => 'required|date',
//         'date_to' => 'required|date|after:date_from',
//         'where' => 'nullable|string',
//         'latitude' => 'required|numeric',
//         'longitude' => 'required|numeric',
//         'radius' => 'required|numeric',
//     ]);

//     $query = Vehicle::query()
//         ->where('status', 'available')
//         // Filter by created_at date range
//         ->whereBetween('created_at', [
//             $validated['date_from'] . ' 00:00:00',
//             $validated['date_to'] . ' 23:59:59'
//         ])
//         // Calculate distance using Haversine formula
//         ->selectRaw('*, ( 6371 * acos( 
//             cos(radians(?)) * cos(radians(latitude)) * 
//             cos(radians(longitude) - radians(?)) + 
//             sin(radians(?)) * sin(radians(latitude)) 
//         ) * 1000 ) AS distance', [
//             $validated['latitude'],
//             $validated['longitude'],
//             $validated['latitude']
//         ])
//         // Filter by radius (in meters)
//         ->havingRaw('distance <= ?', [$validated['radius']]);

//     // Apply location search if 'where' parameter is provided
//     if (!empty($validated['where'])) {
//         $locationParts = array_map('trim', explode(',', $validated['where']));
//         $query->where(function ($q) use ($locationParts) {
//             foreach ($locationParts as $part) {
//                 $searchTerm = '%' . trim($part) . '%';
//                 $q->orWhere('location', 'like', $searchTerm);
//             }
//         });
//     }

//     // Order by distance
//     $query->orderBy('distance');

//     // Get paginated results
//     $vehicles = $query->paginate(15)->withQueryString();
    
//     return Inertia::render('SearchResults', [
//         'vehicles' => $vehicles,
//         'filters' => $validated,
//         'pagination_links' => $vehicles->links()->toHtml(),
//     ]);
// }

// public function search(Request $request)
// {
//     $validated = $request->validate([
//         'date_from' => 'nullable|date', // Make date_from optional
//         'date_to' => 'nullable|date|after:date_from', // Make date_to optional
//         'where' => 'nullable|string',
//         'latitude' => 'required|numeric',
//         'longitude' => 'required|numeric',
//         'radius' => 'required|numeric',
//     ]);

//     $query = Vehicle::query()
//         ->where('status', 'available');

//     // Conditionally filter by created_at date range if date_from and date_to are provided
//     if (!empty($validated['date_from']) && !empty($validated['date_to'])) {
//         $query->whereBetween('created_at', [
//             $validated['date_from'] . ' 00:00:00',
//             $validated['date_to'] . ' 23:59:59'
//         ]);
//     }

//     // Calculate distance using Haversine formula
//     $query->selectRaw('*, ( 6371 * acos( 
//         cos(radians(?)) * cos(radians(latitude)) * 
//         cos(radians(longitude) - radians(?)) + 
//         sin(radians(?)) * sin(radians(latitude)) 
//     ) * 1000 ) AS distance', [
//         $validated['latitude'],
//         $validated['longitude'],
//         $validated['latitude']
//     ])
//     // Filter by radius (in meters)
//     ->havingRaw('distance <= ?', [$validated['radius']]);

//     // Apply location search if 'where' parameter is provided
//     if (!empty($validated['where'])) {
//         $locationParts = array_map('trim', explode(',', $validated['where']));
//         $query->where(function ($q) use ($locationParts) {
//             foreach ($locationParts as $part) {
//                 $searchTerm = '%' . trim($part) . '%';
//                 $q->orWhere('location', 'like', $searchTerm);
//             }
//         });
//     }

//     // Order by distance
//     $query->orderBy('distance');

//     // Get paginated results
//     $vehicles = $query->paginate(15)->withQueryString();
    
//     return Inertia::render('SearchResults', [
//         'vehicles' => $vehicles,
//         'filters' => $validated,
//         'pagination_links' => $vehicles->links()->toHtml(),
//     ]);
// }

// public function search(Request $request)
// {
//     $validated = $request->validate([
//         'date_from' => 'nullable|date', // Make date_from optional
//         'date_to' => 'nullable|date|after:date_from', // Make date_to optional
//         'where' => 'nullable|string',
//         'latitude' => 'required|numeric',
//         'longitude' => 'required|numeric',
//         'radius' => 'required|numeric', // Radius is in meters (from frontend)
//     ]);

//     // Convert radius from meters to kilometers
//     $radiusInKm = $validated['radius'] / 1000;

//     $query = Vehicle::query()
//         ->where('status', 'available');

//     // Conditionally filter by created_at date range if date_from and date_to are provided
//     if (!empty($validated['date_from']) && !empty($validated['date_to'])) {
//         $query->whereBetween('created_at', [
//             $validated['date_from'] . ' 00:00:00',
//             $validated['date_to'] . ' 23:59:59'
//         ]);
//     }

//     // Calculate distance using Haversine formula (in kilometers)
//     $query->selectRaw('*, ( 6371 * acos( 
//         cos(radians(?)) * cos(radians(latitude)) * 
//         cos(radians(longitude) - radians(?)) + 
//         sin(radians(?)) * sin(radians(latitude)) 
//     ) ) AS distance_in_km', [
//         $validated['latitude'],
//         $validated['longitude'],
//         $validated['latitude']
//     ])
//     // Filter by radius (in kilometers)
//     ->havingRaw('distance_in_km <= ?', [$radiusInKm]);

//     // Apply location search if 'where' parameter is provided
//     if (!empty($validated['where'])) {
//         $locationParts = array_map('trim', explode(',', $validated['where']));
//         $query->where(function ($q) use ($locationParts) {
//             foreach ($locationParts as $part) {
//                 $searchTerm = '%' . trim($part) . '%';
//                 $q->orWhere('location', 'like', $searchTerm);
//             }
//         });
//     }

//     // Order by distance
//     $query->orderBy('distance_in_km');

//     // Get paginated results
//     $vehicles = $query->paginate(3)->withQueryString();
//     $vehicles->getCollection()->transform(function ($vehicle) {
//         $vehicle->distance_in_km = intval($vehicle->distance_in_km); // Truncate decimal part
//         return $vehicle;
//     });

//     return Inertia::render('SearchResults', [
//         'vehicles' => $vehicles,
//         'filters' => $validated,
//         'pagination_links' => $vehicles->links()->toHtml(),
//     ]);
// }

public function search(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'category_id' => 'nullable|integer',
            'seating_capacity' => 'nullable|integer',
            'brand' => 'nullable|string',
            'transmission' => 'nullable|string|in:automatic,manual',
            'fuel' => 'nullable|string|in:petrol,diesel,electric',
            'price_range' => 'nullable|string',
            'color' => 'nullable|string',
            'mileage' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
            'where' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|numeric',
        ]);

        // Fetch filter options
        $categories = VehicleCategory::pluck('name', 'id');
        $brands = Vehicle::distinct('brand')->pluck('brand');
        $colors = Vehicle::distinct('color')->pluck('color');
        $seatingCapacities = Vehicle::distinct('seating_capacity')->pluck('seating_capacity');
     
       
        // Base query
        $query = Vehicle::query()->where('status', 'available');

        // Apply filters
        if (!empty($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }
        if (!empty($validated['seating_capacity'])) {
            $query->where('seating_capacity', $validated['seating_capacity']);
        }
        if (!empty($validated['brand'])) {
            $query->where('brand', $validated['brand']);
        }
        if (!empty($validated['transmission'])) {
            $query->where('transmission', $validated['transmission']);
        }
        if (!empty($validated['fuel'])) {
            $query->where('fuel', $validated['fuel']);
        }
        if (!empty($validated['price_range'])) {
            $range = explode('-', $validated['price_range']);
            $query->whereBetween('price_per_day', [(int)$range[0], (int)$range[1]]);
        }
        if (!empty($validated['color'])) {
            $query->where('color', $validated['color']);
        }
        if (!empty($validated['mileage'])) {
            $range = explode('-', $validated['mileage']);
            $query->whereBetween('mileage', [(int)$range[0], (int)$range[1]]);
        }

        // Date range filter
        if (!empty($validated['date_from']) && !empty($validated['date_to'])) {
            $query->whereBetween('created_at', [
                $validated['date_from'] . ' 00:00:00',
                $validated['date_to'] . ' 23:59:59'
            ]);
        }

        // Location search
        if (!empty($validated['where'])) {
            $locationParts = array_map('trim', explode(',', $validated['where']));
            $query->where(function ($q) use ($locationParts) {
                foreach ($locationParts as $part) {
                    $searchTerm = '%' . trim($part) . '%';
                    $q->orWhere('location', 'like', $searchTerm);
                }
            });
        }

        // Distance filter (Haversine formula)
        if (!empty($validated['latitude']) && !empty($validated['longitude']) && !empty($validated['radius'])) {
            $radiusInKm = $validated['radius'] / 1000; // Convert meters to kilometers
            $query->selectRaw('*, ( 6371 * acos( 
                cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * sin(radians(latitude)) 
            ) ) AS distance_in_km', [
                $validated['latitude'],
                $validated['longitude'],
                $validated['latitude']
            ])
            ->havingRaw('distance_in_km <= ?', [$radiusInKm])
            ->orderBy('distance_in_km');
        }

        // Paginate results
        $vehicles = $query->paginate(3)->withQueryString();

        // Transform distance_in_km to integer
        $vehicles->getCollection()->transform(function ($vehicle) {
            if (isset($vehicle->distance_in_km)) {
                $vehicle->distance_in_km = intval($vehicle->distance_in_km);
            }
            return $vehicle;
        });
      
        // Return Inertia response
        return Inertia::render('SearchResults', [
            'vehicles' => $vehicles,
            'filters' => $validated,
            'pagination_links' => $vehicles->links()->toHtml(),
            'categories' => $categories,
            'brands' => $brands,
            'colors' => $colors,
            'seatingCapacities' => $seatingCapacities,
        ]);
    }
}


