<script lang="ts" setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { X } from 'lucide-vue-next'

withDefaults(
  defineProps<{
    isOpen?: boolean
  }>(),
  {
    isOpen: false,
  },
)

const emits = defineEmits(['close'])
</script>

<template>
  <TransitionRoot as="template" :show="isOpen">
    <Dialog class="relative z-10" @close="emits('close')">
      <TransitionChild
        as="template"
        enter="ease-in-out duration-500"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in-out duration-500"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity dark:bg-gray-800/75" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
            <TransitionChild
              as="template"
              enter="transform transition ease-in-out duration-500 sm:duration-700"
              enter-from="translate-x-full"
              enter-to="translate-x-0"
              leave="transform transition ease-in-out duration-500 sm:duration-700"
              leave-from="translate-x-0"
              leave-to="translate-x-full"
            >
              <DialogPanel class="pointer-events-auto w-screen max-w-2xl">
                <div
                  class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl dark:bg-gray-700"
                >
                  <div class="px-4 sm:px-6">
                    <div class="flex items-start justify-between">
                      <DialogTitle class="text-base font-semibold text-gray-900 dark:text-white">
                        <slot name="title" />
                      </DialogTitle>
                      <div class="ml-3 flex h-7 items-center">
                        <button
                          type="button"
                          class="relative rounded-md bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-white"
                          @click="emits('close')"
                        >
                          <span class="absolute -inset-2.5" />
                          <span class="sr-only">Close panel</span>
                          <X class="size-6" aria-hidden="true" />
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="relative mt-6 flex-1 px-4 sm:px-6">
                    <slot />
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
