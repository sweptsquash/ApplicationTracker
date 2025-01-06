<script setup>
import { useColorMode, useCycleList } from '@vueuse/core'
import { LaptopMinimal, Moon, Sun } from 'lucide-vue-next'
import { watchEffect } from 'vue-demi'

const mode = useColorMode({ emitAuto: true })

const { state, next } = useCycleList(['dark', 'light', 'auto'], { initialValue: mode })

watchEffect(() => (mode.value = state.value))
</script>

<template>
  <button @click="next()">
    <Moon v-if="state === 'dark'" aria-hidden="true" class="inline-block h-6 w-6 align-middle" />
    <Sun v-if="state === 'light'" aria-hidden="true" class="inline-block h-6 w-6 align-middle" />
    <LaptopMinimal
      v-if="state === 'auto'"
      aria-hidden="true"
      class="inline-block h-6 w-6 align-middle"
    />
  </button>
</template>
