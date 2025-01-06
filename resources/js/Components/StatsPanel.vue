<script lang="ts" setup>
import { TrendingDown, TrendingUp } from 'lucide-vue-next'

defineProps<{
  stats: Array<{
    title: string
    data: Array<{
      name: string
      stat: string | number
      hideStat?: boolean
      previousStat?: string | number
      changeType?: 'increase' | 'decrease'
      change?: string
    }>
  }>
}>()
</script>

<template>
  <div class="space-y-4">
    <div v-for="{ title, data: items } in stats" :key="title">
      <h3 class="text-base font-semibold text-gray-900">{{ title }}</h3>
      <dl
        class="mt-5 grid grid-cols-1 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow md:grid-cols-3 md:divide-x md:divide-y-0"
      >
        <div v-for="item in items" :key="item.name" class="px-4 py-5 sm:p-6">
          <dt class="text-base font-normal text-gray-900">{{ item.name }}</dt>
          <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
            <template v-if="!item.hideStat">
              <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                {{ item.stat }}
                <span class="ml-2 text-sm font-medium text-gray-500">
                  from {{ item.previousStat }}
                </span>
              </div>

              <div
                :class="[
                  item.changeType === 'increase'
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800',
                  'inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium md:mt-2 lg:mt-0',
                ]"
              >
                <TrendingUp
                  v-if="item.changeType === 'increase'"
                  class="-ml-1 mr-0.5 size-5 shrink-0 self-center text-green-500"
                  aria-hidden="true"
                />
                <TrendingDown
                  v-else
                  class="-ml-1 mr-0.5 size-5 shrink-0 self-center text-red-500"
                  aria-hidden="true"
                />
                <span class="sr-only">
                  {{ item.changeType === 'increase' ? 'Increased' : 'Decreased' }} by
                </span>
                {{ item.change }}
              </div>
            </template>
            <template v-else>
              <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                {{ item.stat }}
              </div>
            </template>
          </dd>
        </div>
      </dl>
    </div>
  </div>
</template>
