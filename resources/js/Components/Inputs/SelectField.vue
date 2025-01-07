<script lang="ts" setup>
import { isEmpty } from 'lodash'
import { ChevronDown } from 'lucide-vue-next'

const props = withDefaults(
  defineProps<{
    label?: string
    id?: string
    name?: string
    required?: boolean
    autocomplete?: string
    options?: Array<{ value: string; label: string }>
    errors?: { [key: string]: any }
  }>(),
  {
    id: undefined,
    name: undefined,
    label: undefined,
    required: false,
    autocomplete: undefined,
    options: undefined,
    errors: undefined,
  },
)

const model = defineModel<string>()

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
        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-gray-600 dark:text-white dark:outline-gray-500 dark:focus:outline-white sm:text-sm/6"
        :class="{
          'text-red-900 outline-red-300 placeholder:text-red-300 focus:outline-red-600 dark:outline-red-300':
            hasError,
        }"
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
    <p v-if="hasError" :id="name ? `${name}-error` : 'error'" class="mt-2 text-sm text-red-600">
      {{ error }}
    </p>
  </div>
</template>
