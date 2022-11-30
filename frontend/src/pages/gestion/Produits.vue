<script setup lang="ts">
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import { reactive, ref, onMounted } from 'vue'
import { AgGridVue } from 'ag-grid-vue3'
import { getAll, store, update, deleteItem } from '/@src/api/produitsApi'
import { getAll as getFournisseurs } from '/@src/api/fournisseursApi'
import { getAll as getGammes } from '/@src/api/gammesApi'
import { useDarkmode } from '/@src/stores/darkmode'
import dayjs from 'dayjs'

const isLoading = ref<boolean>(false)
const isLoadingg = ref<boolean>(false)
const produits = ref<Array<any>>([])
const fournisseurs = ref<Array<any>>([])
const gammes = ref<Array<any>>([])
const showEditModal = ref<boolean>(false)

const state = reactive<any>({
  id: null,
  name: null,
  gamme_id: null,
  fournisseur_id: null,
})
const rules = {
  id: {},
  name: { required },
  gamme_id: { required },
  fournisseur_id: { required },
}
const $v = useVuelidate(rules, state)

const columns = ref<Array<any>>([
  { headerName: '#', field: 'id' },
  { headerName: 'Produit', field: 'name' },
  { headerName: 'Slug', field: 'slug' },
  { headerName: 'Gamme', field: 'gamme.name' },
  { headerName: 'Fournisseur', field: 'fournisseur.name' },
  {
    headerName: 'Date Creation',
    field: 'created_at',
    valueFormatter: (params: any): string => dayjs(params.value).format('DD/MM/YYYY'),
  },
])

onMounted(async (): Promise<void> => {
  await getProduits()
  fournisseurs.value = await getFournisseurs()
  gammes.value = await getGammes()
})

const getProduits = async (): Promise<void> => {
  produits.value = await getAll()
}

const editItem = (event: any): void => {
  const element: any = produits.value.find((item: any): boolean => item.id == event.data.id)
  if (element) {
    for (let item of Object.keys(state)) state[item] = element[item]
    showEditModal.value = true
  }
}

const saveUpdateItem = async (): Promise<void> => {
  isLoading.value = true
  $v.value.$touch()
  if (await $v.value.$validate()) {
    let response: boolean = state.id ? await update(state.id, { ...state }) : await store({ ...state })
    if (response) {
      await getProduits()
      showEditModal.value = false
    }
  }
  isLoading.value = false
}

const deleteItemm = async (): Promise<void> => {
  isLoadingg.value = true
  let response: boolean = await deleteItem(state.id)
  if (response) {
    await getProduits()
    showEditModal.value = false
  }
  isLoadingg.value = false
}

const createItem = async (): Promise<void> => {
  state.id = null
  state.name = null
  state.gamme_id = null
  state.fournisseur_id = null
  showEditModal.value = true
}
</script>

<template>
  <div>
    <div style="display: flex; flex-direction: row; justify-content: end">
      <VButton :loading="isLoading" raised color="primary" icon="feather:plus" @click="createItem"
        >Nouveau</VButton
      >
    </div>

    <div class="my-4">
      <ag-grid-vue
        :class="!useDarkmode().isDark ? 'ag-theme-alpine' : 'ag-theme-alpine-dark'"
        animate-rows="true"
        row-selection="single"
        style="width: 100%; height: 477px"
        :column-defs="columns"
        :row-data="produits"
        :default-col-def="{ resizable: true, flex: 1, sortable: true, unSortIcon: true }"
        pagination="true"
        pagination-auto-page-size="true"
        @row-clicked="editItem"
      >
      </ag-grid-vue>

      <VModal
        :open="showEditModal"
        actions="right"
        size="large"
        :title="state.id ? 'Modifier Produit' : 'Nouveau Produit'"
        cancel-label="Annuler"
        @close="showEditModal = false"
      >
        <template #content>
          <form class="modal-form">
            <div class="field">
              <label>Produit</label>
              <VControl icon="mdi:text" :has-error="$v.name.$invalid && $v.name.$dirty">
                <input v-model="$v.name.$model" type="text" class="input" autocomplete="off" />
              </VControl>
            </div>

            <div class="field">
              <label>Fournisseur</label>
              <VControl
                icon="mdi:text"
                class="has-icons-left"
                :class="$v.fournisseur_id.$invalid && $v.fournisseur_id.$dirty ? 'select-has-error' : ''"
              >
                <div class="select">
                  <select v-model="$v.fournisseur_id.$model">
                    <option v-for="(item, index) in fournisseurs" :key="index" :value="item.id">
                      {{ item.name }}
                    </option>
                  </select>
                </div>
              </VControl>
            </div>

            <div class="field">
              <label>Gamme</label>
              <VControl
                icon="mdi:text"
                class="has-icons-left"
                :class="$v.gamme_id.$invalid && $v.gamme_id.$dirty ? 'select-has-error' : ''"
              >
                <div class="select">
                  <select v-model="$v.gamme_id.$model">
                    <option v-for="(item, index) in gammes" :key="index" :value="item.id">{{ item.name }}</option>
                  </select>
                </div>
              </VControl>
            </div>
          </form>
        </template>
        <template #action>
          <VButton
            v-if="state.id"
            :loading="isLoadingg"
            raised
            color="danger"
            icon="feather:trash"
            @click="deleteItemm"
            >Supprimer
          </VButton>
          <VButton :loading="isLoading" raised color="primary" icon="feather:save" @click="saveUpdateItem"
            >Enregistrer
          </VButton>
        </template>
      </VModal>
    </div>
  </div>
</template>
