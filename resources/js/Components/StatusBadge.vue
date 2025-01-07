<script lang="ts" setup>
const props = defineProps<{
  status: string
}>()

const badgeClass = computed(() => {
  switch (props.status) {
    case 'hired':
      return 'bg-green-50 text-green-700 ring-green-600/20'
    case 'rejected':
      return 'bg-red-50 text-red-700 ring-red-600/10'
    case 'interviewing':
      return 'bg-yellow-50 text-yellow-800 ring-yellow-600/20'
    case 'offer':
      return 'bg-indigo-50 text-indigo-700 ring-indigo-700/10'
    case 'withdrawn':
      return 'bg-pink-50 text-pink-700 ring-pink-700/10'
    default:
      return 'bg-gray-50 text-gray-600 ring-gray-500/10'
  }
})
</script>

<template>
  <span
    :class="[
      'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
      badgeClass,
    ]"
  >
    {{ useCapitalizeFirstLetter(status) }}
  </span>
</template>
