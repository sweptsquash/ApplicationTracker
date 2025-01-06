<script lang="ts" setup>
const props = withDefaults(
  defineProps<{
    title?: string | null
    description?: string
  }>(),
  {
    title: null,
    description: 'The helpful application tracker',
  },
)

const url = computed(() => usePage().props.location)

const pageTitle = computed(() =>
  props.title ? `${props.title} - Application Tracker` : 'Application Tracker',
)
</script>

<template>
  <InertiaHead :title="pageTitle">
    <link rel="canonical" :content="url" />
    <meta name="description" :content="description" />
    <meta name="author" content="SweptSquash" />
  </InertiaHead>
</template>
