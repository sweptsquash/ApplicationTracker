<script lang="ts" setup>
withDefaults(
  defineProps<{
    type?: 'text' | 'email' | 'password'
    placeholder?: string
    label?: string
    id?: string
    name?: string
    autocomplete?: string
    required?: boolean
  }>(),
  {
    type: 'text',
    placeholder: 'Enter a value',
    autocomplete: undefined,
    id: undefined,
    name: undefined,
    label: undefined,
    required: false,
  },
)

const model = defineModel<string>()
</script>

<template>
  <div>
    <label
      v-if="label"
      :id="`${name}-label`"
      :for="name"
      class="block text-sm/6 font-medium text-gray-900"
      :class="{ required }"
    >
      {{ label }}
    </label>
    <div class="mt-2">
      <input
        :id
        v-model="model"
        :type
        :name
        :autocomplete
        :required
        :aria-required="required"
        :aria-labelledby="label ? `${name}-label` : undefined"
        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
      />
    </div>
  </div>
</template>
