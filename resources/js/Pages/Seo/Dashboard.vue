<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-bold">SEO Dashboard</h1>
    
    <!-- SEO Tools Navigation -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold mb-4">SEO Tools</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
          <Link :href="route('seo.ubersuggest.index')" class="flex items-center justify-between">
            <span class="text-blue-600 font-medium">Ubersuggest</span>
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
          </Link>
          <p class="text-sm text-gray-600 mt-1">Keyword research and suggestions</p>
        </div>

        <div class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
          <Link :href="route('seo.analyze')" class="flex items-center justify-between">
            <span class="text-green-600 font-medium">SEO Analyzer</span>
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </Link>
          <p class="text-sm text-gray-600 mt-1">Analyze website SEO performance</p>
        </div>

        <div class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
          <Link :href="route('rank-tracker.index')" class="flex items-center justify-between">
            <span class="text-purple-600 font-medium">Rank Tracker</span>
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </Link>
          <p class="text-sm text-gray-600 mt-1">Track keyword rankings</p>
        </div>

        <div class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
          <Link :href="route('answer-the-public')" class="flex items-center justify-between">
            <span class="text-orange-600 font-medium">AnswerThePublic</span>
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
          </Link>
          <p class="text-sm text-gray-600 mt-1">Find question-based keywords</p>
        </div>
      </div>
    </div>

    <!-- Projects Section -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold mb-4">Projects</h2>
      <div v-if="projects.length === 0" class="text-gray-500">
        No projects found
      </div>
      <div v-else>
        <div v-for="project in projects" :key="project.id" class="border-b pb-4 mb-4">
          <h3 class="font-medium">{{ project.name }}</h3>
          <p class="text-sm text-gray-600">{{ project.description }}</p>
        </div>
      </div>
    </div>

    <!-- Keywords Section -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold mb-4">Keywords</h2>
      <div v-if="keywords.length === 0" class="text-gray-500">
        No keywords found
      </div>
      <div v-else>
        <div v-for="keyword in keywords" :key="keyword.id" class="border-b pb-4 mb-4">
          <p class="font-medium">{{ keyword.keyword }}</p>
          <p class="text-sm text-gray-600">{{ keyword.volume }} searches per month</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  projects: Array,
  keywords: Array
})
</script>