<template>
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-lg">
            <!-- Header with back button -->
            <div class="p-4 border-b">
                <Link href="/s" 
                      class="text-blue-500 hover:text-blue-700 mb-4 inline-block">
                    ← Back to Search
                </Link>
                <h1 class="text-2xl font-bold mt-2">{{ vehicle.name }}</h1>
            </div>

            <div class="grid grid-cols-2 gap-6 p-6">
                <!-- Left Column -->
                <div>
                    <img :src="vehicle.image_url" 
                         :alt="vehicle.name"
                         class="w-full h-96 object-cover rounded-lg">
                    
                    <div class="mt-6 space-y-4">
                        <h2 class="text-xl font-semibold">Vehicle Details</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Model</p>
                                <p class="font-semibold">{{ vehicle.model }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Year</p>
                                <p class="font-semibold">{{ vehicle.year }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Price per day</p>
                                <p class="font-semibold text-green-600">₹{{ vehicle.price_per_day }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div id="detail-map" class="h-96 rounded-lg mb-6"></div>
                    
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">Location</h2>
                        <div>
                            <p class="text-gray-600">Address</p>
                            <p class="font-semibold">{{ vehicle.address }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">City</p>
                            <p class="font-semibold">{{ vehicle.city }}, {{ vehicle.state }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Country</p>
                            <p class="font-semibold">{{ vehicle.country }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
    vehicle: Object
})

onMounted(() => {
    const map = L.map('detail-map').setView([props.vehicle.latitude, props.vehicle.longitude], 15)
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map)

    L.marker([props.vehicle.latitude, props.vehicle.longitude])
        .bindPopup(`
            <div class="text-center">
                <h3 class="font-semibold">${props.vehicle.name}</h3>
                <p class="text-green-600">₹${props.vehicle.price_per_day}/day</p>
            </div>
        `)
        .addTo(map)
})
</script>

<style>
@import 'leaflet/dist/leaflet.css';
</style>