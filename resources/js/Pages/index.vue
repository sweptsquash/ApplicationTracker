<script lang="ts" setup>
defineProps<{
  applications: App.Application[]
  // TODO: Add Stats
}>()

const isConfirmResetOpen = ref(false)

const isAddApplicationOpen = ref(false)

const isSubmitting = ref(false)

const form = reactive({
  title: '',
  role: '',
  salary: '',
  status: '',
  url: '',
})

function addApplication() {
  // TODO
}

function resetApplications() {
  // TODO
}

watch(
  isAddApplicationOpen,
  (isOpen) => {
    if (!isOpen) {
      form.title = ''
      form.role = ''
      form.salary = ''
      form.status = ''
      form.url = ''
    }
  },
  { deep: true },
)
</script>

<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <StatsPanel
      v-if="applications.length"
      :stats="[
        {
          title: 'Total',
          data: [
            {
              name: 'Test',
              stat: 3,
              hideStat: true,
            },
            {
              name: 'Test',
              stat: 3,
              hideStat: true,
            },
            {
              name: 'Test',
              stat: 3,
              hideStat: true,
            },
          ],
        },
        {
          title: 'Last 30 Days',
          data: [
            {
              name: 'Test',
              stat: 3,
              previousStat: 10,
              changeType: 'decrease',
              change: '7%',
            },
            {
              name: 'Test',
              stat: 3,
              previousStat: 10,
              changeType: 'decrease',
              change: '7%',
            },
            {
              name: 'Test',
              stat: 3,
              previousStat: 10,
              changeType: 'decrease',
              change: '7%',
            },
          ],
        },
      ]"
    />

    <div class="mt-4 sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold text-gray-900">Applications</h1>
      </div>
      <div v-if="applications.length" class="mt-4 flex flex-col gap-2 sm:ml-16 sm:mt-0 sm:flex-row">
        <button type="button" class="btn btn-primary">Add Application</button>
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
            v-if="applications.length === 0"
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
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No applications</h3>
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
          <div v-else class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                  >
                    Name
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    Title
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    Email
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    Role
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    Status
                  </th>
                  <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                    <span class="sr-only">Edit</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="application in applications" :key="application.id">
                  <td
                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                  >
                    {{ application.date }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ application.title }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ application.role }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ application.salary }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ application.status }}
                  </td>
                  <td
                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                  >
                    <a href="#" class="text-indigo-600 hover:text-indigo-900">
                      Edit
                      <span class="sr-only">, {{ application.title }}</span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <Modal :is-open="isAddApplicationOpen" @close="isAddApplicationOpen = false">
      <form class="space-y-4">
        <h1 class="border-b text-base/7 font-semibold text-gray-900">Add Application</h1>

        <InputField id="title" v-model="form.title" name="title" label="Title" required />

        <div class="flex flex-row gap-x-2">
          <button type="button" class="btn btn-primary grow" @click="isAddApplicationOpen = false">Cancel</button>
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
      @close="isConfirmResetOpen = false"
      @cancel="isConfirmResetOpen = false"
      @confirm="resetApplications"
    >
      Are you sure you want to reset your applications? This action cannot be undone.
    </ModalConfirm>
  </div>
</template>
