<script setup lang="ts">
import useVuelidate from '@vuelidate/core'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { required, email, minLength } from '@vuelidate/validators'
import { reactive, ref, onMounted } from 'vue'
import { useNotyf } from '/@src/composable/useNotyf'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useRouter } from 'vue-router'
import { UpdateSelf } from '/@src/api/usersApi'
import MainHeader from '/@src/components/partials/MainHeader.vue'
import SaveBar from '/@src/components/forms/Situation/SaveBar.vue'

useViewWrapper().setPageTitle('Profile')
useHead({ title: 'Profile' })

const api = useApi()
const notif = useNotyf()
const userSession = useUserSession()
const isLoading = ref<boolean>(false)
const router = useRouter()

const state = reactive<any>({
  name: '',
  email: '',
  oldPassword: '',
  password: '',
})
const rules = {
  name: { required, minLength: minLength(3) },
  email: { required, email },
  oldPassword: { required },
  password: { minLength: minLength(8) },
}
const $v = useVuelidate(rules, state)

onMounted((): void => {
  state.name = userSession.user?.name
  state.email = userSession.user?.email
})

const submitForm = async (): Promise<void> => {
  isLoading.value = true
  $v.value.$touch()
  if (await $v.value.$validate()) {
    let response: boolean = await UpdateSelf({ ...state })
    if (response) {
      notif.success('Profile modifié avec succès')
      await api.post('/api/logout')
      userSession.logoutUser()
      router.push('/auth/login')
    }
  }
  isLoading.value = false
}
</script>

<template>
  <div class="is-navbar-lg">
    <MainHeader :tab-index="0" :show-profile="true" :navs="[]" @change-tab="() => {}" />
    <form class="form-layout my-4" @submit.prevent>
      <div class="form-outer">
        <SaveBar
          title="Info. Utilisateur"
          button-text="Enregistrer"
          icon="feather:save"
          :is-loading="isLoading"
          @validate="submitForm"
        />
        <div class="form-body">
          <div class="columns is-multiline">
            <div class="column is-6">
              <VField>
                <label>Nom</label>
                <VControl icon="mdi:user" :has-error="$v.name.$invalid && $v.name.$dirty">
                  <input v-model="$v.name.$model" type="text" class="input" autocomplete="off" />
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField>
                <label>Email</label>
                <VControl icon="mdi:mail" :has-error="$v.email.$invalid && $v.email.$dirty">
                  <input v-model="$v.email.$model" type="email" class="input" autocomplete="off" />
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField>
                <label>Anncien mot de passe</label>
                <VControl icon="mdi:password" :has-error="$v.oldPassword.$invalid && $v.oldPassword.$dirty">
                  <input v-model="$v.oldPassword.$model" type="password" class="input" autocomplete="off" />
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField>
                <label>Mot de passe</label>
                <VControl icon="mdi:password" :has-error="$v.password.$invalid && $v.password.$dirty">
                  <input v-model="$v.password.$model" type="password" class="input" autocomplete="off" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
