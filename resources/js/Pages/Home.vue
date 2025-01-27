<template>
  <div class="container mx-auto p-4">
    <form @submit.prevent="submit" class="space-y-4">
      <div class="search-container">
        <input
          v-model="form.where"
          type="text"
          placeholder="Where are you going?"
          @input="handleSearchInput"
          class="w-full p-2 border rounded"
        />
        <div v-if="searchResults.length" class="search-results">
          <div
            v-for="result in searchResults"
            :key="result.id"
            @click="selectLocation(result)"
            class="p-2 hover:bg-gray-100 cursor-pointer"
          >
            {{ result.properties?.label || 'Unknown Location' }}
          </div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <input
          v-model="form.date_from"
          type="date"
          class="p-2 border rounded"
        />
        <input
          v-model="form.date_to"
          type="date"
          class="p-2 border rounded"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-blue-500 text-white p-2 rounded"
      >
        Search
      </button>
    </form>

    <!-- Map Container -->
    <div id="map" class="w-full h-64 mt-4"></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import L from 'leaflet' // Import Leaflet

const form = ref({
  where: '',
  date_from: '',
  date_to: '',
  latitude: null,
  longitude: null,
  radius: 831867.4340914232,
  page:1,
})

const searchResults = ref([])
let map = null // Map instance
let marker = null // Marker instance

const handleSearchInput = async () => {
  if (form.value.where.length < 3) return

  try {
    const response = await axios.get(
      `/api/geocoding/autocomplete?text=${encodeURIComponent(form.value.where)}`
    )
    searchResults.value = response.data.features
  } catch (error) {
    console.error('Error fetching locations:', error)
  }
}

const selectLocation = (result) => {
  form.value.where = result.properties?.label || 'Unknown Location'
  form.value.latitude = result.geometry.coordinates[1]
  form.value.longitude = result.geometry.coordinates[0]
  searchResults.value = []

  // Update map with the selected location
  const latLng = [form.value.latitude, form.value.longitude]

  if (map) {
    map.setView(latLng, 13) // Move the map to the selected location
    if (marker) {
      marker.setLatLng(latLng) // Update marker position
    } else {
      marker = L.marker(latLng).addTo(map) // Add marker if it doesn't exist
    }
  }
}

const submit = () => {
  router.get('/s', form.value)
}

onMounted(() => {
  // Initialize the map
  map = L.map('map').setView([20.5937, 78.9629], 5) // Default to India

  // Add tile layer (OpenStreetMap)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
  }).addTo(map)
})
</script>

<style>
#map {
  height: 400px;
  width: 100%;
  margin-top: 1rem;
}
</style>
