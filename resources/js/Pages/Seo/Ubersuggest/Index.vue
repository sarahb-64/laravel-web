<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Ubersuggest Keyword Research</h1>
    
    <form @submit.prevent="searchKeywords" class="mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block mb-2">Keyword</label>
          <input v-model="form.keyword" type="text" class="w-full p-2 border rounded">
        </div>
        <div>
          <label class="block mb-2">Location</label>
          <input v-model="form.location" type="text" class="w-full p-2 border rounded">
        </div>
        <div>
          <label class="block mb-2">Language</label>
          <input v-model="form.language" type="text" class="w-full p-2 border rounded">
        </div>
      </div>
      <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Search Keywords
      </button>
    </form>

    <div v-if="loading" class="text-center py-4">
      Loading...
    </div>

    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
      {{ error }}
    </div>

    <div v-if="results" class="mt-6">
      <h2 class="text-xl font-bold mb-4">Keyword Suggestions</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Keyword</th>
              <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Search Volume</th>
              <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">CPC</th>
              <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Competition</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="result in results" :key="result.keyword">
              <td class="px-6 py-4 whitespace-nowrap">{{ result.keyword }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ result.volume }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ result.cpc }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ result.competition }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        keyword: '',
        location: 'United States',
        language: 'en'
      },
      results: null,
      loading: false,
      error: null
    }
  },
  methods: {
    async searchKeywords() {
      this.loading = true;
      this.error = null;
      this.results = null;

      try {
        const response = await this.$inertia.post(route('seo.ubersuggest.suggest'), this.form);
        this.results = response.data;
      } catch (error) {
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>