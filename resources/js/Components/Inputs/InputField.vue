<script lang="ts" setup>
import { FunctionalComponent } from 'vue'

withDefaults(
  defineProps<{
    type?: 'text' | 'email' | 'password' | 'number'
    placeholder?: string
    label?: string
    id?: string
    name?: string
    autocomplete?: string
    required?: boolean
    min?: number
    max?: number
    step?: number
    icon?: FunctionalComponent
  }>(),
  {
    type: 'text',
    placeholder: 'Enter a value',
    autocomplete: undefined,
    id: undefined,
    name: undefined,
    label: undefined,
    required: false,
    min: undefined,
    max: undefined,
    step: undefined,
    icon: undefined,
  },
)

const model = defineModel<string | number>()
</script>

<template>
  <div>
    <label
      v-if="label"
      :id="name ? `${name}-label` : undefined"
      :for="name ? `${name}-label` : undefined"
      class="block text-sm/6 font-medium text-gray-900"
      :class="{ required }"
    >
      {{ label }}
    </label>
    <div class="mt-2" :class="{ flex: !!icon }">
      <div
        v-if="!!icon"
        class="flex shrink-0 items-center rounded-l-md bg-white px-3 text-base text-gray-500 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6"
      >
        <component :is="icon" class="h-4 w-4" />
      </div>
      <input
        :id
        v-model="model"
        :type
        :name
        :autocomplete
        :required
        :min
        :max
        :step
        :aria-required="required"
        :aria-labelledby="name ? `${name}-label` : undefined"
        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
        :class="{ 'rounded-l-none border-l-0': !!icon }"
      />
    </div>
  </div>
</template>
