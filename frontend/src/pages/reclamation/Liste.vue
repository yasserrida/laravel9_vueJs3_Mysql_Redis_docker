<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { AgGridVue } from 'ag-grid-vue3'
import { getReclamantionsList } from '/@src/api/reclamationsApi'
import { useDarkmode } from '/@src/stores/darkmode'

const gridApi = ref<any>(null)
const gridColumnApi = ref<any>(null)
const isLoading = ref<boolean>(false)
const reclamations = ref<Array<any>>([])
const totalLignes = ref<number>(1)
const perPage = ref<number>(1)
const page = ref<number>(1)

const columns = ref<Array<any>>([
  { headerName: '#', field: 'id' },
  { headerName: 'N contrat', field: 'numero_contrat' },
  {
    headerName: 'Canal',
    field: 'canal',
    valueFormatter: (params: any): string =>
      params.value ? (params.value.toLowerCase().replaceAll('_', ' ') as string) : '',
  },
  {
    headerName: 'Qualification',
    field: 'qualification',
    valueFormatter: (params: any): string =>
      params.value ? (params.value.toLowerCase().replaceAll('_', ' ') as string) : '',
  },
  {
    headerName: 'Reclamant',
    field: 'reclamant',
    valueFormatter: (params: any): string =>
      params.value ? (params.value.toLowerCase().replaceAll('_', ' ') as string) : '',
  },
])

const onGridReady = (params: any): void => {
  gridApi.value = params.api
  gridColumnApi.value = params.columnApi
}

onMounted(async (): Promise<void> => {
  await getReclamations()
})

const getReclamations = async (sort: any = null): Promise<void> => {
  isLoading.value = true
  let { data, total, per_page } = await getReclamantionsList({
    params: { page: page.value, ...sort },
  })
  reclamations.value = data
  totalLignes.value = total
  perPage.value = per_page
  isLoading.value = false
}

const pagination = async (val: number): Promise<void> => {
  if (page.value != val) {
    page.value = val
    await getReclamations()
  }
}

const postSortRows = async (): Promise<void> => {
  let sortState: any = gridColumnApi.value.getColumnState().filter((s: any): boolean => s.sort != null)
  if (sortState.length) await getReclamations({ sort: sortState[0].colId, sordOrder: sortState[0].sort })
  else await getReclamations()
}
</script>

<template>
  <div class="mt-2">
    <VLoader size="large" center="smooth" :translucent="true" :active="isLoading" class="my-4">
      <ag-grid-vue
        style="width: 100%; height: 441px"
        :class="!useDarkmode().isDark ? 'ag-theme-alpine' : 'ag-theme-alpine-dark'"
        animate-rows="true"
        row-selection="single"
        :on-sort-changed="postSortRows"
        :column-defs="columns"
        :row-data="reclamations"
        :default-col-def="{ resizable: true, flex: 1, filter: false, sortable: true, unSortIcon: true }"
        @grid-ready="onGridReady"
      />
      <div style="display: flex; align-items: center; justify-content: end">
        <VFlexPagination
          v-if="totalLignes / perPage > 1"
          :item-per-page="perPage"
          :total-items="totalLignes"
          :current-page="page"
          :max-links-displayed="5"
          :no-router="true"
          @update-current-page="pagination"
        />
      </div>
    </VLoader>
  </div>
</template>
