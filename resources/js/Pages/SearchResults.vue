<template>
  <div class="container mx-auto p-4">
    <!-- Filter Section -->
    <div class="mb-4 bg-white rounded-lg shadow p-4">
      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Category Filter -->
        <div>
          <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
          <select v-model="filters.category_id" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>

        <!-- Seating Capacity Filter -->
        <div>
          <label for="seating_capacity" class="block text-sm font-medium text-gray-700">Seating Capacity</label>
          <select v-model="filters.seating_capacity" id="seating_capacity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Any</option>
            <option v-for="capacity in seatingCapacities" :key="capacity" :value="capacity">{{ capacity }}</option>
          </select>
        </div>

        <!-- Brand Filter -->
        <div>
          <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
          <select v-model="filters.brand" id="brand" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">All Brands</option>
            <option v-for="brand in brands" :key="brand" :value="brand">{{ brand }}</option>
          </select>
        </div>

        <!-- Transmission Filter -->
        <div>
          <label for="transmission" class="block text-sm font-medium text-gray-700">Transmission</label>
          <select v-model="filters.transmission" id="transmission" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Any</option>
            <option value="automatic">Automatic</option>
            <option value="manual">Manual</option>
          </select>
        </div>

        <!-- Fuel Type Filter -->
        <div>
          <label for="fuel" class="block text-sm font-medium text-gray-700">Fuel Type</label>
          <select v-model="filters.fuel" id="fuel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Any</option>
            <option value="petrol">Petrol</option>
            <option value="diesel">Diesel</option>
            <option value="electric">Electric</option>
          </select>
        </div>

        <!-- Price Range Filter -->
        <div>
          <label for="price_range" class="block text-sm font-medium text-gray-700">Price Range</label>
          <select v-model="filters.price_range" id="price_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Any</option>
            <option value="0-1000">₹0 - ₹1000</option>
            <option value="1000-5000">₹1000 - ₹5000</option>
            <option value="5000-10000">₹5000 - ₹10000</option>
            <option value="10000+">₹10000+</option>
          </select>
        </div>

        <!-- Color Filter -->
        <div>
          <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
          <select v-model="filters.color" id="color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Any</option>
            <option v-for="color in colors" :key="color" :value="color">{{ color }}</option>
          </select>
        </div>

        <!-- Mileage Filter -->
        <div>
          <label for="mileage" class="block text-sm font-medium text-gray-700">Mileage</label>
          <select v-model="filters.mileage" id="mileage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Any</option>
            <option value="0-50000">0 - 50,000 km</option>
            <option value="50000-100000">50,000 - 100,000 km</option>
            <option value="100000+">100,000+ km</option>
          </select>
        </div>
      </div>

      <!-- Apply Filters Button -->
      <div class="mt-4">
        <button @click="applyFilters" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          Apply Filters
        </button>
      </div>
    </div>

    <!-- Vehicle List and Map -->
    <div class="flex gap-4">
      <!-- Left Column - Vehicle List -->
      <div class="w-1/2 space-y-4">
        <div v-if="!vehicles.data || vehicles.data.length === 0" class="text-center text-gray-500">
          No vehicles available at the moment.
        </div>

        <div v-for="vehicle in vehicles.data" :key="vehicle.id" 
             class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow">
            <div class="flex gap-4">
                <img :src="vehicle.image_url" :alt="vehicle.location" 
                     class="w-32 h-32 object-cover rounded-lg">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">{{ vehicle.location }}</h3>
                    <p class="text-gray-600">{{ vehicle.model }} {{ vehicle.year }}</p>
                    <p class="text-gray-500">{{ vehicle.address }}</p>
                    <p class="text-gray-500">{{ vehicle.latitude }}</p>
                    <p class="text-gray-500">{{ vehicle.longitude }}</p>
                    <p class="text-green-600 font-semibold">
                        ₹{{ vehicle.price_per_day }}/day
                    </p>
                    <Link :href="`/vehicle/${vehicle.id}`" 
                          class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                      View Details
                    </Link>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
          <div v-html="pagination_links"></div>
        </div>
      </div>

      <!-- Right Column - Map -->
      <div class="w-1/2 sticky top-4 h-[calc(100vh-2rem)]">
          <div class="bg-white rounded-lg shadow h-full">
              <div id="map" class="h-full rounded-lg"></div>
          </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { onMounted, watch, ref } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import axios from 'axios';

const props = defineProps({
  vehicles: Object,
  filters: Object,
  pagination_links: String,
  categories: Array, // Pass categories from backend
  brands: Array, // Pass brands from backend
  colors: Array, // Pass colors from backend
  seatingCapacities: Array, // Pass seating capacities from backend
})

const vehicles = ref(props.vehicles)
const paginationLinks = ref(props.pagination_links)

const filters = ref({
  category_id: props.filters.category_id || '',
  seating_capacity: props.filters.seating_capacity || '',
  brand: props.filters.brand || '',
  transmission: props.filters.transmission || '',
  fuel: props.filters.fuel || '',
  price_range: props.filters.price_range || '',
  color: props.filters.color || '',
  mileage: props.filters.mileage || '',
})

let map = null
let markers = []

// Fetch filtered vehicles
const applyFilters = async () => {
  try {
    const response = await axios.get('/s', {
      params: filters.value,
      headers: { 'Accept': 'application/json' },
    });
    console.log(response.data);
    // vehicles.value = response.data.vehicles;
    // paginationLinks.value = response.data.pagination_links;
    addMarkers();
  } catch (error) {
    //console.error('Error fetching vehicles:', error.response || error.message || error);
  }
}


const initMap = () => {
  if (!vehicles.value.data || vehicles.value.data.length === 0) {
    console.warn('No vehicles data available to initialize map.');
    map = L.map('map', {
      zoomControl: true,
      maxZoom: 18,
      minZoom: 1,
    }).setView([0, 0], 2); // Default to world view
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    return;
  }

  const bounds = L.latLngBounds(vehicles.value.data.map(vehicle => [
    vehicle.latitude,
    vehicle.longitude
  ]))

  map = L.map('map', {
    zoomSnap: 0.25,
    markerZoomAnimation: false,
    preferCanvas: true,
    zoomControl: true,
    maxZoom: 18,
    minZoom: 1
  })

  map.fitBounds(bounds, {
    padding: [50, 50],
    maxZoom: 12
  })
  
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map)

  map.createPane('markers')
  map.getPane('markers').style.zIndex = 1000

  addMarkers()

  setTimeout(() => {
    map.invalidateSize()
    map.fitBounds(bounds, {
      padding: [50, 50],
      maxZoom: 12
    })
  }, 100)
}

const createCustomIcon = (price) => {
  return L.divIcon({
    className: 'custom-div-icon',
    html: `
      <div class="marker-pin">
        <span>₹${price}</span>
      </div>
    `,
    iconSize: [50, 30],
    iconAnchor: [25, 15],
    popupAnchor: [0, -15],
    pane: 'markers'
  })
}

const addMarkers = () => {
  markers.forEach(marker => marker.remove())
  markers = []

  if (!vehicles.value.data || vehicles.value.data.length === 0) {
    console.warn('No vehicles data available to add markers.');
    return;
  }

  const markerGroup = L.featureGroup()

  vehicles.value.data.forEach(vehicle => {
    const marker = L.marker([vehicle.latitude, vehicle.longitude], {
      icon: createCustomIcon(vehicle.price_per_day),
      pane: 'markers'
    })
    .bindPopup(`
      <div class="text-center">
        <h3 class="font-semibold">${vehicle.location}</h3>
        <a href="/vehicle/${vehicle.id}" 
           class="text-blue-500 hover:text-blue-700"
           onclick="window.location.href='/vehicle/${vehicle.id}'; return false;">
          View Details
        </a>
      </div>
    `)
    
    markerGroup.addLayer(marker)
    markers.push(marker)
  })

  markerGroup.addTo(map)

  const groupBounds = markerGroup.getBounds()
  map.fitBounds(groupBounds, {
    padding: [50, 50],
    maxZoom: 12
  })
}

watch(() => props.vehicles, () => {
  vehicles.value = props.vehicles
  if (map) {
    addMarkers()
  }
}, { deep: true })

const handlePaginationClick = (event) => {
  const link = event.target.closest('a');
  if (link) {
    event.preventDefault();
    fetchVehicles(link.href)
  }
}

// Initialize map and markers (same as before)
onMounted(() => {
  initMap()
})
</script>

<style>
/* Your existing styles */
</style>