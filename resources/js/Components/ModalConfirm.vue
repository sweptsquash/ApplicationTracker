<script lang="ts" setup>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { CircleCheckBig, CircleX, LoaderCircle, TriangleAlert } from 'lucide-vue-next'

withDefaults(
  defineProps<{
    isOpen: boolean
    isLoading?: boolean
    confirmText?: string
    cancelText?: string
    type: 'danger' | 'warning' | 'success'
    title: string
  }>(),
  { isOpen: false, isLoading: false, confirmText: 'Confirm', cancelText: 'Cancel' },
)

const emits = defineEmits(['close', 'cancel', 'confirm'])

const typeClasses = {
  danger: 'bg-red-600 hover:bg-red-500',
  warning: 'bg-yellow-600 hover:bg-yellow-500',
  success: 'bg-indigo-600 hover:bg-indigo-500',
}
</script>

<template>
  <TransitionRoot as="template" :show="isOpen">
    <Dialog class="relative z-10" @close="emits('close')">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity dark:bg-gray-800/75" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div
          class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
        >
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel
              class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:w-full sm:max-w-lg"
            >
              <div class="bg-white px-4 pb-4 pt-5 dark:bg-gray-800 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div
                    :class="[
                      'mx-auto flex size-12 shrink-0 items-center justify-center rounded-full sm:mx-0 sm:size-10',
                      { 'bg-yellow-100': type === 'warning', 'bg-indigo-100': type === 'success' },
                    ]"
                  >
                    <CircleX
                      v-if="type === 'danger'"
                      class="size-6 text-red-600"
                      aria-hidden="true"
                    />
                    <TriangleAlert
                      v-else-if="type === 'warning'"
                      class="size-6 text-yellow-600"
                      aria-hidden="true"
                    />
                    <CircleCheckBig v-else class="size-6 text-indigo-600" aria-hidden="true" />
                  </div>
                  <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <DialogTitle
                      as="h3"
                      class="text-base font-semibold text-gray-900 dark:text-white"
                    >
                      {{ title }}
                    </DialogTitle>
                    <div class="mt-2">
                      <p class="text-sm text-gray-500 dark:text-gray-300">
                        <slot />
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="gap-x-2 bg-gray-50 px-4 py-3 dark:bg-gray-600 sm:flex sm:flex-row-reverse sm:px-6"
              >
                <button
                  type="button"
                  :class="[
                    'btn inline-flex items-center justify-center font-semibold text-white shadow-sm ring-0 disabled:cursor-not-allowed disabled:opacity-50',
                    typeClasses[type],
                  ]"
                  :disabled="isLoading"
                  @click="emits('confirm')"
                >
                  <LoaderCircle v-if="isLoading" class="mr-2 size-4 animate-spin text-white" />
                  {{ confirmText }}
                </button>
                <button type="button" class="btn" @click="emits('cancel')">
                  {{ cancelText }}
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
