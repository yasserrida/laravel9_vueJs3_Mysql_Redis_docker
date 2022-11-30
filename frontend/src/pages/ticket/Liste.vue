<script setup lang="ts">
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import { reactive, ref, onMounted } from 'vue'
import { AgGridVue } from 'ag-grid-vue3'
import { getTicketsList, updateTicket, getDocuments } from '/@src/api/ticketsApi'
import { hasRoleOnly } from '/@src/utils/PermissionsHelper'
import { useDarkmode } from '/@src/stores/darkmode'
import dayjs from 'dayjs'

const gridApi = ref<any>(null)
const gridColumnApi = ref<any>(null)
const isLoading = ref<boolean>(false)
const tickets = ref<Array<any>>([])
const jointes = ref<Array<any>>([])
const showEditModal = ref<boolean>(false)
const totalLignes = ref<number>(1)
const perPage = ref<number>(1)
const page = ref<number>(1)

const state = reactive<any>({
  id: null,
  title: null,
  message: null,
  priority: null,
  statut: null,
  label: null,
  categorie: null,
  is_resolved: null,
})
const rules = {
  id: { required },
  title: { required },
  message: { required },
  priority: { required },
  statut: { required },
  label: { required },
  categorie: { required },
  is_resolved: { required },
}
const $v = useVuelidate(rules, state)
const search = reactive<any>({
  priority: null,
  statut: null,
  label: null,
  categorie: null,
  is_resolved: null,
})

const columns = ref<Array<any>>([
  { headerName: '#', field: 'id' },
  { headerName: 'Titre', field: 'title' },
  { headerName: 'Priorité', field: 'priority' },
  { headerName: 'Statut', field: 'statut' },
  { headerName: 'Label', field: 'label' },
  { headerName: 'Catégorie', field: 'categorie' },
  {
    headerName: 'Résolu',
    field: 'is_resolved',
    valueFormatter: (params: any): string => (params.value ? 'Résolu' : 'Non Résolu'),
  },
  {
    headerName: 'Date',
    field: 'created_at',
    valueFormatter: (params: any): string => (params.value ? dayjs(params.value).format('DD/MM/YYYY') : ''),
  },
])

onMounted(async (): Promise<void> => {
  await getTickets()
})

const onGridReady = (params: any): void => {
  gridApi.value = params.api
  gridColumnApi.value = params.columnApi
}

const pagination = async (val: number): Promise<void> => {
  if (page.value != val) {
    page.value = val
    await getTickets()
  }
}

const getTickets = async (sort: any = null): Promise<void> => {
  isLoading.value = true
  const { data, total, per_page } = await getTicketsList({ params: { page: page.value, ...search, ...sort } })
  tickets.value = data
  totalLignes.value = total
  perPage.value = per_page
  isLoading.value = false
}

const postSortRows = async (): Promise<void> => {
  let sortState: any = gridColumnApi.value.getColumnState().filter((s: any): boolean => s.sort != null)
  if (sortState.length) await getTickets({ sort: sortState[0].colId, sordOrder: sortState[0].sort })
  else await getTickets()
}

const editItem = async (event: any): Promise<void> => {
  if (hasRoleOnly('ADMINISTRATEUR')) {
    const element = tickets.value.find((item: any): boolean => item.id == event.data.id)
    if (element) {
      jointes.value = await getDocuments(event.data.id)
      for (let item of Object.keys(state)) state[item] = element[item]
      showEditModal.value = true
    }
  }
}

const updateItem = async (): Promise<void> => {
  isLoading.value = true
  $v.value.$touch()
  if (await $v.value.$validate()) {
    let formData = new FormData()
    for (let item of Object.keys(state)) formData.append(String(item), state[String(item)])
    if (jointes.value.length)
      for (let i: number = 0; i < jointes.value.length; i++) formData.append('files[]', jointes.value[i])
    let response: boolean = await updateTicket(state.id, formData)
    if (response) {
      await getTickets()
      showEditModal.value = false
    }
  }
  isLoading.value = false
}

const showDocuments = (event: any): void => {
  event.preventDefault()
  for (let item of jointes.value)
    window.open(import.meta.env.VITE_API_BASE_URL + '/storage/app/tickets/' + item.file_name, '_blank')
}
</script>

<template>
  <div class="my-4">
    <form
      class="form-layout"
      style="border-left: solid 4px var(--primary) !important; border-radius: 7px !important"
      @submit.prevent
    >
      <div class="form-outer">
        <SaveBar
          title="Recherche"
          button-text="Rechercher"
          icon="feather:search"
          :is-loading="isLoading"
          @validate="getTickets"
        />
        <div class="form-body">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField>
                <label>Label</label>
                <VControl icon="mdi:format-list-numbers" class="has-icons-left">
                  <div class="select">
                    <select v-model="search.label">
                      <option :value="null">-- Choisissez --</option>
                      <option value="BUG">Bug</option>
                      <option value="QUESTION">Question</option>
                      <option value="ENHANCEMENT">Enhancement</option>
                    </select>
                  </div>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField>
                <label>Catégorie</label>
                <VControl icon="mdi:format-list-numbers" class="has-icons-left">
                  <div class="select">
                    <select v-model="search.categorie">
                      <option :value="null">-- Choisissez --</option>
                      <option value="UNCATEGORIZED">UNCATEGORIZED</option>
                      <option value="TECHNIQUE">TECHNIQUE</option>
                    </select>
                  </div>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField>
                <label>Priorité</label>
                <VControl icon="mdi:format-list-numbers" class="has-icons-left">
                  <div class="select">
                    <select v-model="search.priority">
                      <option :value="null">-- Choisissez --</option>
                      <option value="LOW">LOW</option>
                      <option value="MEDIUM">MEDIUM</option>
                      <option value="HIGH">HIGH</option>
                    </select>
                  </div>
                </VControl>
              </VField>
            </div>

            <div class="column is-3">
              <VField>
                <label>Statut</label>
                <VControl icon="mdi:format-list-numbers" class="has-icons-left">
                  <div class="select">
                    <select v-model="search.statut">
                      <option :value="null">-- Choisissez --</option>
                      <option value="OPEN">OPEN</option>
                      <option value="PENDING">PENDING</option>
                      <option value="CLOSED">CLOSED</option>
                    </select>
                  </div>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>
    </form>

    <VLoader size="large" center="smooth" :translucent="true" :active="isLoading" class="mt-4">
      <ag-grid-vue
        :class="!useDarkmode().isDark ? 'ag-theme-alpine' : 'ag-theme-alpine-dark'"
        animate-rows="true"
        row-selection="single"
        style="width: 100%; height: 441px"
        :column-defs="columns"
        :row-data="tickets"
        :default-col-def="{ resizable: true, flex: 1, sortable: true, unSortIcon: true }"
        :on-sort-changed="postSortRows"
        @grid-ready="onGridReady"
        @row-clicked="editItem"
      >
      </ag-grid-vue>
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

    <VModal
      :open="showEditModal"
      actions="right"
      size="large"
      title="Modifier Ticket"
      cancel-label="Annuler"
      @close="showEditModal = false"
    >
      <template #content>
        <form class="modal-form">
          <div class="field">
            <label>Titre</label>
            <VControl icon="mdi:text" :has-error="$v.title.$invalid && $v.title.$dirty">
              <input v-model="$v.title.$model" type="text" class="input" autocomplete="off" />
            </VControl>
          </div>

          <div class="field">
            <label>Message</label>
            <VControl icon="mdi:text" :has-error="$v.message.$invalid && $v.message.$dirty">
              <textarea v-model="$v.message.$model" type="text" class="input" autocomplete="off"></textarea>
            </VControl>
          </div>

          <div class="field">
            <label>Label</label>
            <VControl
              icon="mdi:format-list-numbers"
              class="has-icons-left"
              :class="$v.label.$invalid && $v.label.$dirty ? 'select-has-error' : ''"
            >
              <div class="select">
                <select v-model="$v.label.$model">
                  <option :value="null">-- Choisissez --</option>
                  <option value="BUG">Bug</option>
                  <option value="QUESTION">Question</option>
                  <option value="ENHANCEMENT">Enhancement</option>
                </select>
              </div>
            </VControl>
          </div>

          <div class="field">
            <label>Catégorie</label>
            <VControl
              icon="mdi:format-list-numbers"
              class="has-icons-left"
              :class="$v.categorie.$invalid && $v.categorie.$dirty ? 'select-has-error' : ''"
            >
              <div class="select">
                <select v-model="$v.categorie.$model">
                  <option :value="null">-- Choisissez --</option>
                  <option value="UNCATEGORIZED">UNCATEGORIZED</option>
                  <option value="TECHNIQUE">TECHNIQUE</option>
                </select>
              </div>
            </VControl>
          </div>

          <div class="field">
            <label>Priorité</label>
            <VControl
              icon="mdi:format-list-numbers"
              class="has-icons-left"
              :class="$v.priority.$invalid && $v.priority.$dirty ? 'select-has-error' : ''"
            >
              <div class="select">
                <select v-model="$v.priority.$model">
                  <option :value="null">-- Choisissez --</option>
                  <option value="LOW">LOW</option>
                  <option value="MEDIUM">MEDIUM</option>
                  <option value="HIGH">HIGH</option>
                </select>
              </div>
            </VControl>
          </div>

          <div class="field">
            <label>Statut</label>
            <VControl
              icon="mdi:format-list-numbers"
              class="has-icons-left"
              :class="$v.statut.$invalid && $v.statut.$dirty ? 'select-has-error' : ''"
            >
              <div class="select">
                <select v-model="$v.statut.$model">
                  <option :value="null">-- Choisissez --</option>
                  <option value="OPEN">OPEN</option>
                  <option value="PENDING">PENDING</option>
                  <option value="CLOSED">CLOSED</option>
                </select>
              </div>
            </VControl>
          </div>

          <div class="field">
            <label>Résolu</label>
            <VControl
              icon="mdi:format-list-numbers"
              class="has-icons-left"
              :class="$v.is_resolved.$invalid && $v.is_resolved.$dirty ? 'select-has-error' : ''"
            >
              <div class="select">
                <select v-model="$v.is_resolved.$model">
                  <option :value="null">-- Choisissez --</option>
                  <option :value="1">Résolu</option>
                  <option :value="0">Non Résolu</option>
                </select>
              </div>
            </VControl>
          </div>

          <div v-if="jointes.length" class="field">
            <VField>
              <label>&nbsp;</label>
              <VControl>
                <div class="file is-primary" style="width: 100%" @click="showDocuments($event)">
                  <label class="file-label" style="width: 100%">
                    <input class="file-input" type="file" color="is-primary" />
                    <span class="file-cta" style="width: 100%">
                      <span class="file-icon"
                        ><i class="iconify" aria-hidden="true" data-icon="feather:upload"></i
                      ></span>
                      <span class="file-label">Consulter attachements</span>
                    </span>
                  </label>
                </div>
              </VControl>
            </VField>
          </div>
        </form>
      </template>
      <template #action>
        <VButton :loading="isLoading" raised color="primary" icon="feather:save" @click="updateItem"
          >Enregistrer
        </VButton>
      </template>
    </VModal>
  </div>
</template>
