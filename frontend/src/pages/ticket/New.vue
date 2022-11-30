<script setup lang="ts">
import { required } from '@vuelidate/validators'
import { reactive, ref, nextTick } from 'vue'
import { useNotyf } from '/@src/composable/useNotyf'
import { storeTicket } from '/@src/api/ticketsApi'
import useVuelidate from '@vuelidate/core'

const notif = useNotyf()
const isLoading = ref<boolean>(false)
const jointes = ref<Array<any>>([])

const state = reactive<any>({
  title: null,
  message: null,
  priority: 'LOW',
  label: null,
  categorie: null,
})
const rules = {
  title: { required },
  message: { required },
  priority: { required },
  label: { required },
  categorie: { required },
}
const $v = useVuelidate(rules, state)

const submitForm = async (): Promise<void> => {
  isLoading.value = true
  $v.value.$touch()
  if (await $v.value.$validate()) {
    let formData = new FormData()
    for (let item of Object.keys(state)) formData.append(String(item), state[String(item)])
    if (jointes.value.length)
      for (let i: number = 0; i < jointes.value.length; i++) formData.append('files[]', jointes.value[i])
    let response: boolean = await storeTicket(formData)
    if (response) {
      await nextTick((): void => {
        notif.success('Ticket  créé avec succès')
        state.title = null
        state.message = true
        state.priority = null
        state.statut = 'OPEN'
        state.label = null
        state.categorie = null
        state.is_resolved = null
        jointes.value = []
        $v.value.$reset()
      })
    }
  }
  isLoading.value = false
}

const handleChange = (event: any): void => {
  for (let file of event.target.files) jointes.value.push(file)
}
</script>
<template>
  <form class="form-layout" @submit.prevent>
    <div class="form-outer">
      <SaveBar
        title="Créer une nouvelle ticket"
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
                <label>Titre</label>
                <VControl icon="mdi:text" :has-error="$v.title.$invalid && $v.title.$dirty">
                  <input v-model="$v.title.$model" type="text" class="input" autocomplete="off" />
                </VControl>
              </VField>
            </div>

            <div class="column is-12">
              <VField>
                <label>Message</label>
                <VControl icon="mdi:text" :has-error="$v.message.$invalid && $v.message.$dirty">
                  <textarea v-model="$v.message.$model" type="text" class="input" autocomplete="off"></textarea>
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField>
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
              </VField>
            </div>

            <div class="column is-6">
              <VField>
                <label>Catégorie</label>
                <VControl
                  icon="mdi:format-list-numbers"
                  class="has-icons-left"
                  :class="$v.categorie.$invalid && $v.categorie.$dirty ? 'select-has-error' : ''"
                >
                  <div class="select">
                    <select v-model="$v.categorie.$model">
                      <option :value="null">-- Choisissez --</option>
                      <option value="UNCATEGORIZED">Uncategorized</option>
                      <option value="TECHNIQUE">Technique</option>
                    </select>
                  </div>
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField>
                <label> Priorité</label>
                <VControl
                  icon="mdi:format-list-numbers"
                  class="has-icons-left"
                  :class="$v.priority.$invalid && $v.priority.$dirty ? 'select-has-error' : ''"
                >
                  <div class="select">
                    <select v-model="$v.priority.$model">
                      <option :value="null">-- Choisissez --</option>
                      <option value="LOW">Low</option>
                      <option value="MEDIUM">Medium</option>
                      <option value="HIGH">High</option>
                    </select>
                  </div>
                </VControl>
              </VField>
            </div>

            <div class="column is-6">
              <VField>
                <label>Attachements</label>
                <VControl>
                  <div class="file" :class="jointes.length ? 'is-primary' : ''" style="width: 100%">
                    <label class="file-label" style="width: 100%">
                      <input
                        class="file-input"
                        type="file"
                        multiple
                        accept=".jpg, .jpeg, .pdf"
                        @change="handleChange($event)"
                      />
                      <span class="file-cta" style="width: 100%">
                        <span class="file-icon">
                          <i class="iconify" aria-hidden="true" data-icon="feather:upload"></i>
                        </span>
                        <span class="file-label">Attachements</span>
                      </span>
                    </label>
                  </div>
                </VControl>
              </VField>
            </div>
          </div>
        </VLoader>
      </div>
    </div>
  </form>
</template>
