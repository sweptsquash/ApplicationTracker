<script lang="ts" setup>
import { isEmpty } from 'lodash'
import { TriangleAlert } from 'lucide-vue-next'
import { FunctionalComponent } from 'vue'

const props = withDefaults(
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
    errors?: { [key: string]: unknown }
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
    errors: undefined,
  },
)

const model = defineModel<string | number>()

const hasError = computed(() => {
  if (
    props.name === undefined ||
    props.id === undefined ||
    isEmpty(props.name) ||
    isEmpty(props.id) ||
    props.errors === undefined
  ) {
    return false
  }

  return !!props.errors[props.name ?? props.id]
})

const error = computed(() => {
  if (props.errors === undefined || !hasError.value) {
    return undefined
  }

  if (props.name !== undefined && Object.keys(props.errors).includes(props.name)) {
    return props.errors[props.name]
  }

  if (props.id !== undefined && Object.keys(props.errors).includes(props.id)) {
    return props.errors[props.id]
  }

  return undefined
})
</script>

<template>
  <div>
    <label
      v-if="label"
      :id="name ? `${name}-label` : undefined"
      :for="name ? `${name}-label` : undefined"
      class="block text-sm/6 font-medium text-gray-900 dark:text-white"
      :class="{ required }"
    >
      {{ label }}
    </label>
    <div class="mt-2" :class="{ flex: !!icon }">
      <div
        v-if="!!icon"
        class="flex shrink-0 items-center rounded-l-md bg-white px-3 text-base text-gray-500 outline outline-1 -outline-offset-1 outline-gray-300 dark:bg-gray-600 dark:text-white dark:outline-gray-500 sm:text-sm/6"
      >
        <component :is="icon" class="h-4 w-4" />
      </div>
      <div class="grid grow grid-cols-1">
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
          class="col-start-1 row-start-1 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-gray-600 dark:text-white dark:outline-gray-500 dark:focus:outline-white sm:text-sm/6"
          :class="{
            'rounded-l-none border-l-0': !!icon,
            'text-red-900 outline-red-300 placeholder:text-red-300 focus:outline-red-600 dark:outline-red-300':
              hasError,
          }"
        />
        <TriangleAlert
          v-if="hasError"
          class="pointer-events-none col-start-1 row-start-1 mr-3 size-5 self-center justify-self-end text-red-500 sm:size-4"
        />
      </div>
      <p v-if="hasError" :id="name ? `${name}-error` : 'error'" class="mt-2 text-sm text-red-600">
        {{ error }}
      </p>
    </div>
  </div>
</template>
