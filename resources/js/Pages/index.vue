<script lang="ts" setup>
import VueDatePicker from '@vuepic/vue-datepicker'
import { useColorMode } from '@vueuse/core'
import { Eye, Pencil, PoundSterling } from 'lucide-vue-next'

defineProps<{
  applications: { data: App.Application[]; meta: App.PageMeta }
  stats: App.Stats
  application?: App.Application
  errors?: { [key: string]: unknown }
}>()

const isConfirmResetOpen = ref(false)

const isAddApplicationOpen = ref(false)

const isApplicationSlideOverOpen = ref(false)

const isEditingApplication = ref(false)

const isSubmitting = ref(false)

const isResetting = ref(false)

const currentApplication = ref<App.Application>()

const form = useForm<{
  title: string
  url: string
  company?: string
  salary_period?: 'hourly' | 'daily' | 'weekly' | 'bi-weekly' | 'monthly' | 'yearly'
  salary_type: 'unknown' | 'range' | 'fixed'
  salary_min: number
  salary_max: number
  status: App.ApplicationStatus
  applied_at: string | Date
  notes: string
}>({
  title: '',
  url: '',
  company: undefined,
  salary_period: 'yearly',
  salary_type: 'unknown',
  salary_min: 0,
  salary_max: 0,
  status: 'applied',
  applied_at: new Date(),
  notes: '',
})

const isDark = ref(false)

const salaryTypes = [
  { value: 'unknown', label: 'Unknown / Competitive' },
  { value: 'range', label: 'Range' },
  { value: 'fixed', label: 'Fixed' },
]

const salaryPeriods = [
  { value: '', label: 'Unknown / Not Provided' },
  { value: 'hourly', label: 'Hourly' },
  { value: 'daily', label: 'Daily' },
  { value: 'weekly', label: 'Weekly' },
  { value: 'bi-weekly', label: 'Bi-Weekly' },
  { value: 'monthly', label: 'Monthly' },
  { value: 'yearly', label: 'Yearly' },
]

const statues: { value: App.ApplicationStatus; label: string }[] = [
  { value: 'applied', label: 'Applied' },
  { value: 'awaiting_response', label: 'Awaiting Response' },
  { value: 'interviewing', label: 'Interviewing' },
  { value: 'offer', label: 'Offer' },
  { value: 'rejected', label: 'Rejected' },
  { value: 'withdrawn', label: 'Withdrawn' },
]

onMounted(() => {
  isDark.value = useColorMode().value === 'dark'
})

function addApplication() {
  isSubmitting.value = true

  form.post(route('applications.store'), {
    onSuccess: () => {
      isAddApplicationOpen.value = false
      isSubmitting.value = false
    },
  })
}

function resetApplications() {
  isResetting.value = true

  useForm({}).delete(route('applications.reset'), {
    onSuccess: () => {
      isResetting.value = false
      isConfirmResetOpen.value = false
    },
  })
}

watch(
  isAddApplicationOpen,
  (isOpen) => {
    if (!isOpen) {
      form.reset()
    }
  },
  { deep: true },
)

function fetchApplication(id: number, editable = false) {
  isApplicationSlideOverOpen.value = true
  isEditingApplication.value = editable

  useAxios()
    .get(route('applications.show', id))
    .then((response) => {
      currentApplication.value = response.data
    })
}

const dateFormat = (date: Date) => {
  return useDayJs(date).format('DD/MM/YYYY')
}
</script>

<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <div
      class="mb-4 flex flex-row items-center justify-between border-b border-gray-300 pb-4 dark:border-gray-600"
    >
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Application Tracker</h1>
      <ThemeToggle />
    </div>

    <StatsPanel
      v-if="applications.data.length"
      :stats="[
        {
          title: 'Total',
          data: [
            {
              name: 'Applied',
              stat: stats?.applied,
              hideTrend: true,
            },
            {
              name: 'Interviewing',
              stat: stats?.interviewing,
              hideTrend: true,
            },
            {
              name: 'Rejections',
              stat: stats?.rejections,
              hideTrend: true,
            },
            {
              name: 'Awaiting Response',
              stat: stats?.awaiting,
              hideTrend: true,
            },
            {
              name: 'Offer',
              stat: stats?.offers,
              hideTrend: true,
            },
            {
              name: 'Withdrawn',
              stat: stats?.withdrawn,
              hideTrend: true,
            },
          ],
        },
      ]"
    />

    <div class="mt-4 sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold text-gray-900 dark:text-white">Applications</h1>
      </div>
      <div
        v-if="applications.data.length"
        class="mt-4 flex flex-col gap-2 sm:ml-16 sm:mt-0 sm:flex-row"
      >
        <button type="button" class="btn btn-primary" @click="isAddApplicationOpen = true">
          Add Application
        </button>
        <button
          type="button"
          class="block rounded-md bg-red-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
          @click="isConfirmResetOpen = true"
        >
          Clear Applications
        </button>
      </div>
    </div>
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div
            v-if="applications.data.length === 0"
            class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400"
          >
            <svg
              class="mx-auto size-12 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                vector-effect="non-scaling-stroke"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
              />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">
              No applications
            </h3>
            <p class="mt-1 text-sm text-gray-500">Get started by adding a new application.</p>
            <div class="mt-6">
              <button
                type="button"
                class="btn btn-primary inline-block"
                @click="isAddApplicationOpen = true"
              >
                New application
              </button>
            </div>
          </div>
          <template v-else>
            <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <th
                      scope="col"
                      class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6"
                    >
                      Job Title
                    </th>
                    <th
                      scope="col"
                      class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                    >
                      Salary
                    </th>
                    <th
                      scope="col"
                      class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                    >
                      Status
                    </th>
                    <th
                      scope="col"
                      class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                    >
                      Applied
                    </th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                      <span class="sr-only">Options</span>
                    </th>
                  </tr>
                </thead>
                <tbody
                  class="divide-y divide-gray-200 bg-white dark:divide-gray-600 dark:bg-gray-700"
                >
                  <tr v-for="app in applications.data" :key="app.id">
                    <td
                      class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6"
                    >
                      <p>
                        <a :href="app.url" class="link" target="_blank" rel="noopener noreferrer">
                          {{ app.title }}
                        </a>
                      </p>
                      <span class="text-xs font-normal text-gray-500 dark:text-gray-400">
                        {{ app.company?.name ?? 'N/A' }}
                      </span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white">
                      {{ useFormatSalary(app) }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white">
                      <StatusBadge :status="app.status" />
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white">
                      {{ useDayJs(app.created_at).format('DD/MM/YYYY') }}
                    </td>
                    <td
                      class="relative space-x-2 whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                    >
                      <button
                        class="btn btn-primary inline-block"
                        :aria-label="`View, ${app.title}`"
                        :title="`View, ${app.title}`"
                        @click="fetchApplication(app.id)"
                      >
                        <Eye class="size-4" />
                      </button>
                      <button
                        class="btn inline-block"
                        :aria-label="`Edit, ${app.title}`"
                        :title="`Edit, ${app.title}`"
                        @click="fetchApplication(app.id, true)"
                      >
                        <Pencil class="size-4" />
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <Pagination :meta="applications.meta" />
          </template>
        </div>
      </div>
    </div>

    <Modal :is-open="isAddApplicationOpen" @close="isAddApplicationOpen = false">
      <form class="space-y-4">
        <h1 class="border-b pb-4 text-base/7 font-semibold">Add Application</h1>

        <InputField
          id="title"
          v-model="form.title"
          name="title"
          label="Job Title"
          required
          :errors
        />

        <InputField
          id="company"
          v-model="form.company"
          name="company"
          label="Company Name"
          :errors
        />

        <InputField id="url" v-model="form.url" name="url" label="URL" required :errors />

        <SelectField
          id="salary_type"
          v-model="form.salary_type"
          :options="salaryTypes"
          name="salary_type"
          label="Salary Type"
          required
          :errors
        />

        <SelectField
          v-if="form.salary_type !== 'unknown'"
          id="salary_period"
          v-model="form.salary_period"
          :options="salaryPeriods"
          name="salary_period"
          label="Salary Period"
          required
          :errors
        />

        <template v-if="form.salary_type === 'fixed'">
          <InputField
            id="salary"
            v-model="form.salary_min"
            type="number"
            :min="0"
            :step="10"
            name="salary"
            label="Salary"
            required
            :icon="PoundSterling"
            :errors
          />
        </template>
        <template v-else-if="form.salary_type === 'range'">
          <div class="flex flex-row items-center gap-2">
            <InputField
              id="salaryMin"
              v-model="form.salary_min"
              type="number"
              :min="0"
              :step="10"
              name="salaryMin"
              label="Minimum Salary"
              required
              :icon="PoundSterling"
              :errors
            />
            <InputField
              id="salaryMax"
              v-model="form.salary_max"
              type="number"
              :min="0"
              :step="10"
              name="salaryMax"
              label="Maximum Salary"
              required
              :icon="PoundSterling"
              :errors
            />
          </div>
        </template>

        <SelectField
          id="status"
          v-model="form.status"
          :options="statues"
          name="status"
          label="Application Status"
          required
          :errors
        />

        <VueDatePicker
          v-model="form.applied_at"
          :format="dateFormat"
          :dark="isDark"
          :enable-time-picker="false"
          auto-apply
        />

        <TipTap v-model="form.notes" label="Notes" />

        <div class="flex flex-row gap-x-2">
          <button type="button" class="btn grow" @click="isAddApplicationOpen = false">
            Cancel
          </button>
          <button type="button" class="btn btn-primary grow" @click="addApplication">
            Add Application
          </button>
        </div>
      </form>
    </Modal>

    <ModalConfirm
      title="Clear Applications"
      type="warning"
      :is-open="isConfirmResetOpen"
      :is-loading="isResetting"
      @close="isConfirmResetOpen = false"
      @cancel="isConfirmResetOpen = false"
      @confirm="resetApplications"
    >
      Are you sure you want to reset your applications? This action cannot be undone.
    </ModalConfirm>

    <ApplicationSlideOver
      v-model="currentApplication"
      :is-open="isApplicationSlideOverOpen"
      :editable="isEditingApplication"
      @edit="isEditingApplication = true"
      @close="isApplicationSlideOverOpen = false"
    />
  </div>
</template>
