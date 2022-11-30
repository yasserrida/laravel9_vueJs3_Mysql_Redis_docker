<script setup lang="ts">
import useVuelidate from '@vuelidate/core'
import { required, numeric } from '@vuelidate/validators'
import { reactive, ref, onMounted, nextTick } from 'vue'
import { AgGridVue } from 'ag-grid-vue3'
import { getNotificationsList, updateNotification, deleteNotification } from '/@src/api/notificationsApi'
import { can } from '/@src/utils/PermissionsHelper'
import { useDarkmode } from '/@src/stores/darkmode'

const showEditModal = ref<boolean>(false)
const ItemsList = ref<Array<any>>([])
const totalLignes = ref<number>(1)
const perPage = ref<number>(1)
const page = ref<number>(1)
const isLoading = ref<boolean>(false)
const isLoadingg = ref<boolean>(false)
const gridApi = ref<any>(null)
const gridColumnApi = ref<any>(null)

const onGridReady = (params: any): void => {
  gridApi.value = params.api
  gridColumnApi.value = params.columnApi
}

const state = reactive<any>({
  id: null,
  name: null,
  status: null,
  temps_traitement: '',
})
const search = reactive<any>({
  id: null,
  name: null,
})
const rules = {
  id: { required },
  name: { required },
  status: { required },
  temps_traitement: { required, numeric },
}
const $v = useVuelidate(rules, state)

const columns = ref<Array<any>>([
  {
    headerName: 'Numéro de notification',
    field: 'id',
    valueFormatter: (params: any): string => 'Notification ' + params.value,
  },
  { headerName: 'Nom de notification', field: 'name' },
  {
    headerName: 'Statut',
    field: 'temps_traitement',
    sortable: true,
    filter: false,
    cellRenderer: (params: any): string => {
      return `<label class="checkbox is-solid is-${
        !params.data.status
          ? 'danger'
          : params.data.temps_traitement <= 3
          ? 'success'
          : params.data.temps_traitement <= 7
          ? 'warning'
          : 'danger'
      }"><input type="checkbox" disabled checked><span></span></label>`
    },
  },
])

onMounted(async (): Promise<void> => {
  await getNotifications()
})

const getNotifications = async (sort = null, sordOrder = null): Promise<void> => {
  isLoading.value = true
  const { data, total, per_page } = await getNotificationsList({
    params: { page: page.value, ...search, sort: sort, sordOrder: sordOrder },
  })
  ItemsList.value = data
  totalLignes.value = total
  perPage.value = per_page
  isLoading.value = false
}

const editItem = (event: any): void => {
  if (can('NOTIFICATION-UPDATE')) {
    state.id = event.data.id
    state.name = event.data.name
    state.status = event.data.status
    state.temps_traitement = event.data.temps_traitement
    showEditModal.value = true
  }
}

const updateItem = async (): Promise<void> => {
  isLoading.value = true
  $v.value.$touch()
  if (await $v.value.$validate()) {
    let response: boolean = await updateNotification(state.id, { ...state })
    if (response) {
      await nextTick((): void => {
        getNotifications()
        showEditModal.value = false
        $v.value.$reset()
      })
    }
  }
  isLoading.value = false
}

const deleteItem = async (val: any): Promise<void> => {
  isLoadingg.value = true
  let response: boolean = await deleteNotification(val.id)
  if (response) {
    await getNotifications()
    showEditModal.value = false
  }
  isLoadingg.value = false
}

const pagination = async (val: number): Promise<void> => {
  if (page.value != val) {
    page.value = val
    await getNotifications()
  }
}

const postSortRows = async (): Promise<void> => {
  let sortState: any = gridColumnApi.value.getColumnState().filter((s: any): boolean => s.sort != null)
  if (sortState.length) {
    await getNotifications(sortState[0].colId, sortState[0].sort)
  } else await getNotifications()
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
          @validate="getNotifications"
        />
        <div class="form-body">
          <div class="form-fieldset">
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField>
                  <label>Numéro notification</label>
                  <VControl icon="mdi:text">
                    <input v-model="search.id" class="input" autocomplete="off" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6">
                <VField>
                  <label>Nom notification</label>
                  <VControl icon="mdi:text">
                    <input v-model="search.name" class="input" autocomplete="off" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <VLoader size="large" center="smooth" :translucent="true" :active="isLoading" class="my-4">
      <ag-grid-vue
        style="width: 100%; height: 477px"
        :class="!useDarkmode().isDark ? 'ag-theme-alpine' : 'ag-theme-alpine-dark'"
        animate-rows="true"
        row-selection="single"
        :on-sort-changed="postSortRows"
        :column-defs="columns"
        :row-data="ItemsList"
        :default-col-def="{ resizable: true, flex: 1, filter: false, sortable: true, unSortIcon: true }"
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
      title="Modifier Notification"
      cancel-label="Annuler"
      @close="showEditModal = false"
    >
      <template #content>
        <form class="modal-form">
          <div class="field">
            <label>Libellé</label>
            <VControl icon="mdi:text" :has-error="$v.name.$invalid && $v.name.$dirty">
              <input v-model="$v.name.$model" type="text" class="input" />
            </VControl>
          </div>

          <div class="field">
            <label>Statut</label>
            <VControl
              icon="mdi:format-list-numbers"
              class="has-icons-left"
              :class="$v.status.$invalid && $v.status.$dirty ? 'select-has-error' : ''"
            >
              <div class="select">
                <select v-model="$v.status.$model">
                  <option :value="true">Active</option>
                  <option :value="false">Inactive</option>
                </select>
              </div>
            </VControl>
          </div>

          <div class="field">
            <label>Temps de traitement (Jours)</label>
            <VControl
              icon="mdi:alarm-clock"
              :has-error="$v.temps_traitement.$invalid && $v.temps_traitement.$dirty"
            >
              <VIMaskInput
                v-model="$v.temps_traitement.$model"
                type="text"
                autocomplete="off"
                class="input"
                :options="{
                  mask: '000',
                }"
              />
            </VControl>
          </div>
        </form>
      </template>
      <template #action>
        <VButton
          v-if="can('NOTIFICATION-DELETE')"
          raised
          color="danger"
          icon="feather:trash"
          :loading="isLoadingg"
          @click="deleteItem(state)"
          >Supprimer
        </VButton>
        <VButton raised color="primary" icon="feather:save" :loading="isLoading" @click="updateItem"
          >Enregistrer
        </VButton>
      </template>
    </VModal>
  </div>
</template>
