<script lang="ts" setup>
onMounted(() => {
  const timezone = useDayJs.tz.guess() ?? 'Europe/London'
  useDayJs.tz.setDefault(timezone)
})
</script>

<template>
  <main class="flex min-h-screen flex-col">
    <div class="p-4">
      <slot />
    </div>
    <AppFooter />
  </main>
</template>
