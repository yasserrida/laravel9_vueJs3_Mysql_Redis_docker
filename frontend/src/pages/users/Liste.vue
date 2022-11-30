<script setup lang="ts">
import useVuelidate from '@vuelidate/core'
import { required, email, minLength, requiredIf } from '@vuelidate/validators'
import { reactive, ref, onMounted, nextTick } from 'vue'
import { AgGridVue } from 'ag-grid-vue3'
import {
  getRolesList,
  getUserssList,
  getResponsablesList,
  UpdateUser,
  getUserPermissionsList,
  getPermissionsList,
} from '/@src/api/usersApi'
import { useDarkmode } from '/@src/stores/darkmode'

const isLoading = ref<boolean>(false)
const isLoadingg = ref<boolean>(false)
const users = ref<Array<any>>([])
const responsables = ref<Array<any>>([])
const roles = ref<Array<any>>([])
const showEditModal = ref<boolean>(false)
const permissions = ref<Array<any>>([])

const state = reactive<any>({
  id: null,
  name: '',
  email: '',
  poste_code: '',
  password: '',
  role_id: null,
  owner: null,
  active: '',
  permissions: [],
})
const rules = {
  id: { required },
  name: { required, minLength: minLength(3) },
  email: { required, email },
  poste_code: { required },
  password: {},
  role_id: { required },
  owner: { requiredIf: requiredIf((): boolean => state.role_id == 5) },
  active: {},
  permissions: {},
}
const $v = useVuelidate(rules, state)

const columns = ref<Array<any>>([
  { headerName: '#', field: 'id' },
  { headerName: 'Nom', field: 'name' },
  { headerName: 'Email', field: 'email' },
  { headerName: 'Poste code', field: 'poste_code' },
  { headerName: 'Role', field: 'role' },
  { headerName: 'Responsable', field: 'parent' },
  {
    headerName: 'Statut',
    field: 'active',
    sortable: true,
    filter: false,
    cellRenderer: (params: any): string =>
      params.data.active == 1
        ? (`<button title="Activé" class="button button v-button is-success is-circle is-light" readonly>
          <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--feather" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" data-icon="feather:power"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><path d="M12 2v10"></path></g></svg></span>
        </button>` as string)
        : (`<button title="Désactivé" class="button button v-button is-danger is-circle is-light" readonly>
          <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--feather" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" data-icon="feather:power"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><path d="M12 2v10"></path></g></svg></span>
        </button>` as string),
  },
])

onMounted(async (): Promise<void> => {
  await getUsers()
  roles.value = await getRolesList()
  responsables.value = await getResponsablesList()
  permissions.value = await getPermissionsList()
})

const getUsers = async (): Promise<void> => {
  isLoading.value = true
  users.value = await getUserssList()
  isLoading.value = false
}

const editItem = async (event: any): Promise<void> => {
  state.id = event.data.id
  state.name = event.data.name
  state.email = event.data.email
  state.poste_code = event.data.poste_code
  state.role_id = event.data.role_id
  state.active = event.data.active
  state.owner = event.data.owner
  state.permissions = await getUserPermissionsList(event.data.id)
  showEditModal.value = true
}

const updateItem = async (loading: boolean = true): Promise<void> => {
  if (loading) isLoading.value = true
  $v.value.$touch()
  if (await $v.value.$validate()) {
    let response: boolean = await UpdateUser(state.id, { ...state })
    if (response) {
      await getUsers()
      await nextTick((): void => {
        showEditModal.value = false
        $v.value.$reset()
      })
    }
  }
  isLoading.value = false
}

const changeItemStatus = async (val: any): Promise<void> => {
  isLoadingg.value = true
  state.id = val.id
  state.name = val.name
  state.email = val.email
  state.poste_code = val.poste_code
  state.role_id = val.role_id
  state.active = val.active == 1 ? 0 : 1
  await updateItem(false)
  isLoadingg.value = false
}
</script>
<template>
  <div>
    <VLoader size="large" center="smooth" :translucent="true" :active="isLoading">
      <ag-grid-vue
        :class="!useDarkmode().isDark ? 'ag-theme-alpine' : 'ag-theme-alpine-dark'"
        animate-rows="true"
        row-selection="single"
        style="width: 100%; height: 477px"
        :column-defs="columns"
        :row-data="users"
        :default-col-def="{ resizable: true, flex: 1, filter: true, sortable: true, unSortIcon: true }"
        pagination="true"
        pagination-auto-page-size="true"
        @row-clicked="editItem"
      >
      </ag-grid-vue>
    </VLoader>
    <VModal
      :open="showEditModal"
      actions="right"
      size="big"
      title="Modifier utilisateur"
      cancel-label="Annuler"
      @close="showEditModal = false"
    >
      <template #content>
        <form class="modal-form form-fieldset">
          <div class="fieldset-heading">
            <h4>Informations</h4>
          </div>

          <div class="columns is-multiline">
            <div class="field column is-6">
              <label>Nom</label>
              <VControl icon="mdi:user" :has-error="$v.name.$invalid && $v.name.$dirty">
                <input v-model="$v.name.$model" type="text" class="input" />
              </VControl>
            </div>

            <div class="field column is-6">
              <label>Responsable</label>
              <VControl
                icon="mdi:account-arrow-right"
                class="has-icons-left"
                :class="$v.owner.$invalid && $v.owner.$dirty ? 'select-has-error' : ''"
              >
                <div class="select">
                  <select v-model="$v.owner.$model">
                    <option :value="null">-- Choisissez --</option>
                    <option v-for="(responsable, index) in responsables" :key="index" :value="responsable.id">
                      {{ responsable.name }}
                    </option>
                  </select>
                </div>
              </VControl>
            </div>

            <div class="field column is-6">
              <label>Email</label>
              <VControl icon="mdi:at" :has-error="$v.email.$invalid && $v.email.$dirty">
                <input v-model="$v.email.$model" type="text" class="input" />
              </VControl>
            </div>

            <div class="field column is-6">
              <label>Poste code</label>
              <VControl icon="mdi:mail" :has-error="$v.poste_code.$invalid && $v.poste_code.$dirty">
                <input v-model="$v.poste_code.$model" type="text" class="input" />
              </VControl>
            </div>

            <div class="field column is-6">
              <label>Role</label>
              <VControl
                icon="mdi:format-list-numbers"
                class="has-icons-left"
                :class="$v.role_id.$invalid && $v.role_id.$dirty ? 'select-has-error' : ''"
              >
                <div class="select">
                  <select v-model="$v.role_id.$model">
                    <option v-for="(role, index) in roles" :key="index" :value="role.id">
                      {{ role.display_name }}
                    </option>
                  </select>
                </div>
              </VControl>
            </div>

            <div class="field column is-6">
              <label>Mot de passe</label>
              <VControl icon="mdi:password">
                <input v-model="$v.password.$model" type="password" class="input" />
              </VControl>
            </div>
          </div>

          <div class="fieldset-heading">
            <h4>Permissions</h4>
          </div>

          <div class="columns is-multiline" style="height: 200px; overflow: auto">
            <div v-for="(permission, index) in permissions" :key="index" class="column is-4">
              <VField>
                <VControl>
                  <VCheckbox
                    v-model="state.permissions"
                    color="primary"
                    :value="permission.id"
                    :label="permission.display_name"
                    :checked="state.permissions.includes(permission.id)"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton
          size="big"
          :color="state.active ? 'danger' : 'success'"
          icon="feather:power"
          :title="state.active ? 'Désactiver' : 'Activer'"
          :loading="isLoadingg"
          @click="changeItemStatus(state)"
          >{{ state.active ? 'Désactiver' : 'Activer' }}</VButton
        >
        <VButton :loading="isLoading" raised color="primary" icon="feather:save" @click="updateItem"
          >Enregistrer</VButton
        >
      </template>
    </VModal>
  </div>
</template>
