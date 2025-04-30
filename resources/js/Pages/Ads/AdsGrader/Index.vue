<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-bold">Ads Grader</h1>
    
    <!-- Campaign Analysis Form -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold mb-4">Analyze Campaign</h2>
      
      <form @submit.prevent="analyzeCampaign" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Platform</label>
          <select v-model="form.platform" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="google">Google Ads</option>
            <option value="facebook">Facebook Ads</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Campaign ID</label>
          <input v-model="form.campaign_id" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Analyze Campaign
        </button>
      </form>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-4">
      <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      Analyzing campaign...
    </div>

    <!-- Error State -->
    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      {{ error }}
    </div>

    <!-- Metrics Display -->
    <div v-if="metrics" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm font-medium text-gray-500">Clicks</h3>
        <p class="text-3xl font-semibold">{{ metrics.clicks }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm font-medium text-gray-500">Impressions</h3>
        <p class="text-3xl font-semibold">{{ metrics.impressions }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm font-medium text-gray-500">CTR</h3>
        <p class="text-3xl font-semibold">{{ metrics.ctr.toFixed(2) }}%</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm font-medium text-gray-500">CPC</h3>
        <p class="text-3xl font-semibold">$ {{ metrics.cpc.toFixed(2) }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm font-medium text-gray-500">Conversions</h3>
        <p class="text-3xl font-semibold">{{ metrics.conversions }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm font-medium text-gray-500">Conversion Rate</h3>
        <p class="text-3xl font-semibold">{{ metrics.conversion_rate.toFixed(2) }}%</p>
      </div>
    </div>

    <!-- Recommendations -->
    <div v-if="recommendations.length > 0" class="space-y-4">
      <h2 class="text-lg font-semibold">Recommendations</h2>
      <div v-for="recommendation in recommendations" :key="recommendation.title" class="bg-white rounded-lg shadow p-6">
        <div :class="`flex items-center p-3 rounded-lg ${getRecommendationColor(recommendation.type)}`">
          <div class="flex-1">
            <h3 class="font-medium">{{ recommendation.title }}</h3>
            <p class="text-sm text-gray-600">{{ recommendation.message }}</p>
          </div>
          <div class="flex-none">
            <svg v-if="recommendation.type === 'warning'" class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <svg v-if="recommendation.type === 'info'" class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 space-y-2">
          <p v-for="suggestion in recommendation.suggestions" :key="suggestion" class="text-sm text-gray-600">
            - {{ suggestion }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const form = ref({
  platform: 'google',
  campaign_id: ''
})

const metrics = ref(null)
const recommendations = ref([])
const loading = ref(false)
const error = ref(null)

const analyzeCampaign = async () => {
  loading.value = true
  error.value = null
  metrics.value = null
  recommendations.value = []

  try {
    const response = await fetch(route('ads.ads-grader.analyze'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        platform: form.value.platform,
        campaign_id: form.value.campaign_id
      })
    })

    if (!response.ok) {
      throw new Error('Failed to analyze campaign')
    }

    const data = await response.json()
    metrics.value = data.metrics
    recommendations.value = data.recommendations
  } catch (err) {
    error.value = err.message || 'An error occurred while analyzing the campaign'
  } finally {
    loading.value = false
  }
}

const getRecommendationColor = (type) => {
  switch (type) {
    case 'warning':
      return 'bg-yellow-50 border border-yellow-200'
    case 'info':
      return 'bg-blue-50 border border-blue-200'
    default:
      return 'bg-gray-50 border border-gray-200'
  }
}
</script>