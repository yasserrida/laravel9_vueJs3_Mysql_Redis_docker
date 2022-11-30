<script setup lang="ts">
import useVuelidate from '@vuelidate/core'
import { required, numeric } from '@vuelidate/validators'
import { reactive, ref, nextTick } from 'vue'
import { useNotyf } from '/@src/composable/useNotyf'
import { storeNotification } from '/@src/api/notificationsApi'
import SaveBar from '/@src/components/forms/Situation/SaveBar.vue'

const notif = useNotyf()
const isLoading = ref<boolean>(false)

const state = reactive<any>({
  name: null,
  status: true,
  temps_traitement: '',
})
const rules = {
  name: { required },
  status: { required },
  temps_traitement: { required, numeric },
}
const $v = useVuelidate(rules, state)

const submitForm = async (): Promise<void> => {
  isLoading.value = true
  $v.value.$touch()
  if (await $v.value.$validate()) {
    let response: boolean = await storeNotification({ ...state })
    if (response) {
      await nextTick((): void => {
        notif.success('Notification créée avec succès')
        state.name = null
        state.status = true
        state.temps_traitement = null
        $v.value.$reset()
      })
    }
  }
  isLoading.value = false
}
</script>

<template>
  <form class="form-layout" @submit.prevent>
    <div class="form-outer">
      <SaveBar
        title="Créer une nouvelle notification"
        button-text="Enregistrer"
        icon="feather:save"
        :is-loading="isLoading"
        @validate="submitForm"
      />
      <div class="form-body">
        <VLoader size="large" center="smooth" :translucent="true" :active="isLoading">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <label>Libellé</label>
                <VControl icon="mdi:text" :has-error="$v.name.$invalid && $v.name.$dirty">
                  <input v-model="$v.name.$model" type="text" class="input" autocomplete="off" />
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField>
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
              </VField>
            </div>

            <div class="column is-6">
              <VField>
                <label>Temps de traitement(Jours)</label>
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
              </VField>
            </div>
          </div>
        </VLoader>
      </div>
    </div>
  </form>
</template>
