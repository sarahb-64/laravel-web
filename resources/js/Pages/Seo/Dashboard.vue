<template>
  <div>
    <h1>SEO Dashboard</h1>
    <div>
      <h2>Projects</h2>
      <pre>{{ projects }}</pre>
    </div>
    <div>
      <h2>Keywords</h2>
      <pre>{{ keywords }}</pre>
    </div>
  </div>
</template>

<script setup>
defineProps(['projects', 'keywords'])
</script>