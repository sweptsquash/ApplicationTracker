<script lang="ts" setup>
import { ExternalLink, Pencil } from 'lucide-vue-next'

withDefaults(
  defineProps<{
    isOpen: boolean
    editable: boolean
  }>(),
  { isOpen: false, editable: false },
)

const emits = defineEmits(['close', 'edit'])

const application = defineModel<App.Application>()
</script>

<template>
  <SlideOver :is-open="isOpen" @close="emits('close')">
    <template #title>
      <h2 class="text-lg font-semibold">Application: {{ application?.title }}</h2>
    </template>
    <template v-if="editable">EDITING</template>
    <template v-else>
      <div class="py-5 sm:py-0">
        <dl class="space-y-8 sm:space-y-0 sm:divide-y sm:divide-gray-200">
          <div class="flex justify-between gap-2 sm:py-5">
            <button
              class="btn btn-primary inline-flex w-1/2 items-center justify-center"
              @click="emits('edit')"
            >
              <Pencil class="mr-2 size-4" />
              Edit
            </button>
            <a
              :href="application?.url"
              class="btn btn-primary inline-flex w-1/2 items-center justify-center"
            >
              Application Page
              <ExternalLink class="ml-2 inline-block size-4" />
            </a>
          </div>

          <div class="sm:flex sm:py-5">
            <dt
              class="text-sm font-medium text-gray-500 dark:text-gray-200 sm:w-40 sm:shrink-0 lg:w-48"
            >
              Company
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:ml-6 sm:mt-0">
              {{ application?.company?.name ?? 'N/A' }}
            </dd>
          </div>

          <div class="sm:flex sm:py-5">
            <dt
              class="text-sm font-medium text-gray-500 dark:text-gray-200 sm:w-40 sm:shrink-0 lg:w-48"
            >
              Salary
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:ml-6 sm:mt-0">
              {{ useFormatSalary(application) }}
            </dd>
          </div>

          <div class="sm:flex sm:py-5">
            <dt
              class="text-sm font-medium text-gray-500 dark:text-gray-200 sm:w-40 sm:shrink-0 lg:w-48"
            >
              Status
            </dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:ml-6 sm:mt-0">
              <StatusBadge v-if="application" :status="application?.status" />
            </dd>
          </div>

          <div class="py-5">
            <h2 class="text-lg font-semibold">Notes</h2>
            <div class="mt-2 text-sm text-gray-900 dark:text-white" v-html="application?.notes" />
          </div>
        </dl>
      </div>
    </template>
  </SlideOver>
</template>
