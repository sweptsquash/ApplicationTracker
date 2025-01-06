<script lang="ts" setup>
import { ChevronDown } from 'lucide-vue-next'

withDefaults(
  defineProps<{
    label?: string
    id?: string
    name?: string
    required?: boolean
    autocomplete?: string
    options?: Array<{ value: string; label: string }>
  }>(),
  {
    id: undefined,
    name: undefined,
    label: undefined,
    required: false,
    autocomplete: undefined,
    options: undefined,
  },
)

const model = defineModel<string>()
</script>

<template>
  <div>
    <label
      v-if="label"
      :id="name ? `${name}-label` : undefined"
      :for="name ? `${name}-label` : undefined"
      class="block text-sm/6 font-medium text-gray-900"
    >
      {{ label }}
    </label>
    <div class="mt-2 grid grid-cols-1">
      <select
        :id
        v-model="model"
        :name
        :autocomplete
        :required
        :aria-required="required"
        :aria-labelledby="name ? `${name}-label` : undefined"
        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
      >
        <option v-for="option in options" :key="`${name}-${option.value}`" :value="option.value">
          {{ option.label }}
        </option>
      </select>
      <ChevronDown
        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
        aria-hidden="true"
      />
    </div>
  </div>
</template>
