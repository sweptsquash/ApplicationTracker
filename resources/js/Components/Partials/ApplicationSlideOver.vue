<script lang="ts" setup>
import VueDatePicker from '@vuepic/vue-datepicker'
import { ExternalLink, Eye, Pencil, PoundSterling } from 'lucide-vue-next'

withDefaults(
  defineProps<{
    isOpen: boolean
    isDark: boolean
    editable: boolean
    errors?: { [key: string]: unknown }
  }>(),
  { errors: undefined },
)

const emits = defineEmits(['close', 'edit', 'view'])

const application = defineModel<App.Application>()

const form = useForm<{
  title: string
  url: string
  company?: string
  salary_period?: App.ApplicationSalaryPeriod
  salary_type: App.ApplicationSalaryType
  salary_min: number
  salary_max: number
  status: App.ApplicationStatus
  applied_at: string | Date
  notes: string
}>({
  title: '',
  url: '',
  company: '',
  salary_period: 'yearly',
  salary_type: 'unknown',
  salary_min: 0,
  salary_max: 0,
  status: 'applied',
  applied_at: new Date(),
  notes: '',
})

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

watch(
  () => application.value,
  (value) => {
    if (value) {
      form.defaults({
        title: application.value?.title ?? '',
        url: application.value?.url ?? '',
        company: application.value?.company?.name,
        salary_period: application.value?.salary_period ?? 'yearly',
        salary_type: application.value?.salary_type ?? 'unknown',
        salary_min: application.value?.salary_min ? application.value?.salary_min / 100 : 0,
        salary_max: application.value?.salary_max ? application.value?.salary_max / 100 : 0,
        status: application.value?.status ?? 'applied',
        applied_at: application.value?.applied_at ?? new Date(),
        notes: application.value?.notes ?? '',
      })

      form.reset()
    }
  },
  { deep: true, immediate: true },
)

const dateFormat = (date: Date) => {
  return useDayJs(date).format('DD/MM/YYYY')
}

function goBackToViewing() {
  form.reset()

  emits('view')
}

function updateApplication() {
  form.put(route('applications.update', application.value?.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()

      goBackToViewing()

      emits('close')
    },
  })
}
</script>

<template>
  <SlideOver :is-open="isOpen" @close="emits('close')">
    <template #title>
      <h2 class="text-lg font-semibold">Application: {{ application?.title }}</h2>
    </template>
    <template v-if="editable">
      <div class="py-5 sm:py-0">
        <dl class="space-y-8 sm:space-y-4 sm:divide-y sm:divide-gray-200">
          <div class="flex justify-between gap-2 sm:py-5">
            <button
              class="btn btn-primary inline-flex flex-1 items-center justify-center"
              @click="goBackToViewing"
            >
              <Eye class="mr-2 size-4" />
              View
            </button>
            <a
              :href="application?.url"
              class="btn btn-primary inline-flex flex-1 items-center justify-center"
            >
              Application Page
              <ExternalLink class="ml-2 inline-block size-4" />
            </a>
          </div>

          <InputField
            id="title"
            v-model="form.title"
            name="title"
            label="Job Title"
            class="pt-2"
            :errors
          />

          <InputField
            id="company"
            v-model="form.company"
            name="company"
            label="Company Name"
            class="pt-2"
            :errors
          />

          <InputField
            id="url"
            v-model="form.url"
            name="url"
            label="URL"
            class="pt-2"
            required
            :errors
          />

          <SelectField
            id="salary_type"
            v-model="form.salary_type"
            :options="salaryTypes"
            name="salary_type"
            label="Salary Type"
            class="pt-2"
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
            class="pt-2"
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
              class="pt-2"
              required
              :icon="PoundSterling"
              :errors
            />
          </template>
          <template v-else-if="form.salary_type === 'range'">
            <div class="flex flex-col items-center gap-2 pt-2 sm:flex-row">
              <InputField
                id="salaryMin"
                v-model="form.salary_min"
                type="number"
                :min="0"
                :step="10"
                name="salaryMin"
                label="Minimum Salary"
                class="flex-1"
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
                class="flex-1"
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
            class="pt-2"
            required
            :errors
          />

          <VueDatePicker
            v-model="form.applied_at"
            :format="dateFormat"
            :dark="isDark"
            :enable-time-picker="false"
            class="pt-2"
            auto-apply
          />

          <TipTap v-model="form.notes" label="Notes" class="pt-2" />

          <div class="flex flex-col gap-2 pt-2 sm:flex-row">
            <button class="btn btn-default flex-1" @click="goBackToViewing">Cancel Changes</button>
            <button class="btn btn-primary flex-1" @click="updateApplication">
              Update Application
            </button>
          </div>
        </dl>
      </div>
    </template>
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

          <div class="sm:flex sm:items-center sm:py-5">
            <dt
              class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-200 sm:w-40 sm:shrink-0 lg:w-48"
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
