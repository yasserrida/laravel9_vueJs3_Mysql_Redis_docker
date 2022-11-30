<script setup lang="ts">
import useVuelidate from '@vuelidate/core'
import { required, email, minLength, requiredIf } from '@vuelidate/validators'
import { reactive, ref, nextTick, onMounted, watch } from 'vue'
import { useNotyf } from '/@src/composable/useNotyf'
import {
  storeUser,
  getResponsablesList,
  getRolesList,
  getPermissionsList,
  getPermissionsListByRole,
} from '/@src/api/usersApi'
import SaveBar from '/@src/components/forms/Situation/SaveBar.vue'

const notif = useNotyf()
const isLoading = ref<boolean>(false)
const responsables = ref<Array<any>>([])
const roles = ref<Array<any>>([])
const permissions = ref<Array<any>>([])

const state = reactive<any>({
  name: '',
  email: '',
  poste_code: '',
  password: '',
  owner: null,
  role_id: null,
  permissions: [],
})
const rules = {
  name: { required, minLength: minLength(3) },
  email: { required, email },
  poste_code: { required },
  password: { required, minLength: minLength(8) },
  owner: { requiredIf: requiredIf((): boolean => state.role_id == 5) },
  role_id: { required },
}
const $v = useVuelidate(rules, state)

watch(
  () => state.role_id,
  async (): Promise<void> => {
    let temp: any[] = await getPermissionsListByRole(state.role_id as string)
    state.permissions = temp && temp.length ? temp : []
  }
)

onMounted(async (): Promise<void> => {
  isLoading.value = true
  responsables.value = await getResponsablesList()
  roles.value = await getRolesList()
  permissions.value = await getPermissionsList()
  isLoading.value = false
})

const submitForm = async (): Promise<void> => {
  isLoading.value = true
  $v.value.$touch()
  if (await $v.value.$validate()) {
    let response: boolean = await storeUser({ ...state })
    if (response) {
      await nextTick((): void => {
        notif.success('Utilisateur créée avec succès')
        state.name = ''
        state.email = ''
        state.poste_code = ''
        state.owner = ''
        state.role_id = null
        $v.value.$reset()
      })
    }
  }
  isLoading.value = false
}
</script>

<template>
  <form class="form-layout" @submit.prevent>
    <VLoader size="large" center="smooth" :translucent="true" :active="isLoading">
      <div class="form-outer">
        <SaveBar
          title="Créer un nouveau utilisateur"
          button-text="Enregistrer"
          icon="feather:save"
          :is-loading="isLoading"
          @validate="submitForm"
        />
        <div class="form-body">
          <div class="form-fieldset">
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField>
                  <label>Nom</label>
                  <VControl icon="mdi:user" :has-error="$v.name.$invalid && $v.name.$dirty">
                    <input v-model="$v.name.$model" type="text" class="input" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6">
                <VField>
                  <label>Email</label>
                  <VControl icon="mdi:at" :has-error="$v.email.$invalid && $v.email.$dirty">
                    <input v-model="$v.email.$model" type="email" class="input" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6">
                <VField>
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
                </VField>
              </div>

              <div class="column is-6">
                <VField>
                  <label>Role</label>
                  <VControl
                    icon="mdi:format-list-numbers"
                    class="has-icons-left"
                    :class="$v.role_id.$invalid && $v.role_id.$dirty ? 'select-has-error' : ''"
                  >
                    <div class="select">
                      <select v-model="$v.role_id.$model">
                        <option :value="null">-- Choisissez --</option>
                        <option v-for="(role, index) in roles" :key="index" :value="role.id">
                          {{ role.display_name }}
                        </option>
                      </select>
                    </div>
                  </VControl>
                </VField>
              </div>

              <div class="column is-6">
                <VField>
                  <label>Poste code</label>
                  <VControl icon="mdi:mail" :has-error="$v.poste_code.$invalid && $v.poste_code.$dirty">
                    <input v-model="$v.poste_code.$model" type="text" class="input" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6">
                <VField>
                  <label>Mot de passe</label>
                  <VControl icon="mdi:password" :has-error="$v.password.$invalid && $v.password.$dirty">
                    <input v-model="$v.password.$model" type="password" class="input" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="form-fieldset">
            <div class="fieldset-heading">
              <h4>Permissions</h4>
            </div>
            <div class="columns is-multiline">
              <div v-for="(permission, index) in permissions" :key="index" class="column is-4">
                <VField>
                  <VControl>
                    <VCheckbox
                      v-model="state.permissions"
                      :value="permission.id"
                      :label="permission.display_name"
                      color="primary"
                    />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VLoader>
  </form>
</template>
