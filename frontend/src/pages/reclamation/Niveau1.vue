<script setup lang="ts">
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import { reactive, ref, onMounted } from 'vue'
import { useNotyf } from '/@src/composable/useNotyf'
import { storeReclamantion } from '/@src/api/reclamationsApi'
import { getProduits } from '/@src/api/commonApi'
import SaveBar from '/@src/components/forms/Situation/SaveBar.vue'

const notif = useNotyf()
const isLoading = ref<boolean>(false)
const produits = ref<Array<any>>([])

const state = reactive<any>({
  contrat_id: null,
  produit_id: null,
  canal: null,
  qualification: null,
  reclamant: null,
  status: null,
  date_mail: '',
  date_courier: '',
})
const rules = {
  contrat_id: {},
  produit_id: {},
  canal: { required },
  qualification: { required },
  reclamant: { required },
  status: { required },
  date_mail: { required },
  date_courier: { required },
}
const $v = useVuelidate(rules, state)

onMounted(async (): Promise<void> => {
  produits.value = await getProduits()
})

const submitForm = async (): Promise<void> => {
  isLoading.value = true
  if (await $v.value.$validate()) {
    let formData = new FormData()
    for (let item of Object.keys(state)) formData.append(String(item), state[String(item)])
    let response: boolean = await storeReclamantion(formData)
    if (!response) {
      isLoading.value = false
      return
    }
    notif.success('Réclamation créée avec succès')
  }
  isLoading.value = false
}
</script>

<template>
  <form class="form-layout" @submit.prevent>
    <div class="form-outer">
      <SaveBar
        title="Enregistrement nouvelle réclamation"
        button-text="Enregistrer"
        icon="feather:save"
        :is-loading="isLoading"
        @validate="submitForm"
      />
      <div class="form-body">
        <div class="form-fieldset">
          <VLoader size="large" center="smooth" :translucent="true" :active="isLoading">
            <div class="fieldset-heading">
              <h4>Informations Contrat</h4>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField>
                  <label>N° de contrat</label>
                  <VControl
                    icon="mdi:clipboard-edit-outline"
                    :has-error="$v.contrat_id.$invalid && $v.contrat_id.$dirty"
                  >
                    <input v-model="$v.contrat_id.$model" type="text" class="input" autocomplete="off" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-6">
                <VField>
                  <label>Produit</label>
                  <VControl icon="mdi:format-align-center" class="has-icons-left">
                    <div class="select">
                      <select v-model="$v.produit_id.$model">
                        <option :value="null">-- Choisissez --</option>
                        <option v-for="(produit, index) in produits" :key="index" :value="produit.id">
                          {{ produit.name }}
                        </option>
                      </select>
                    </div>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="fieldset-heading">
              <h4>Informations Réclamation</h4>
            </div>

            <div class="columns is-multiline">
              <div class="column is-4">
                <VField>
                  <label>Canal d'entrée</label>
                  <VControl
                    icon="mdi:text"
                    class="has-icons-left"
                    :class="$v.canal.$invalid && $v.canal.$dirty ? 'select-has-error' : ''"
                  >
                    <div class="select">
                      <select v-model="$v.canal.$model">
                        <option :value="null">-- Choisissez --</option>
                        <option value="MAIL">Mail</option>
                        <option value="SITE">Site</option>
                        <option value="ESPACE_CLIENT">Espace client</option>
                        <option value="COURRIER">Courrier</option>
                        <option value="LRAR">LRAR</option>
                        <option value="NUMERO_CONTRAT">Numéro de contrat</option>
                      </select>
                    </div>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4">
                <VField>
                  <label>Qualification</label>
                  <VControl
                    icon="mdi:text"
                    class="has-icons-left"
                    :class="$v.qualification.$invalid && $v.qualification.$dirty ? 'select-has-error' : ''"
                  >
                    <div class="select">
                      <select v-model="$v.qualification.$model">
                        <option :value="null">-- Choisissez --</option>
                        <option value="INCOMPREHENSION">Incompréhension</option>
                        <option value="QUALITE_CONTACT">Site</option>
                        <option value="ATTENTE_DELAI">Espace client</option>
                      </select>
                    </div>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4">
                <VField>
                  <label>Réclament</label>
                  <VControl
                    icon="mdi:text"
                    class="has-icons-left"
                    :class="$v.reclamant.$invalid && $v.reclamant.$dirty ? 'select-has-error' : ''"
                  >
                    <div class="select">
                      <select v-model="$v.reclamant.$model">
                        <option :value="null">-- Choisissez --</option>
                        <option value="SOUSCRIPTEUR">Souscripteur</option>
                        <option value="MANDATAIRE">Mandataire</option>
                        <option value="ORGANISME">Organisme</option>
                      </select>
                    </div>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4">
                <VField>
                  <label>Statut</label>
                  <VControl
                    icon="mdi:text"
                    class="has-icons-left"
                    :class="$v.status.$invalid && $v.status.$dirty ? 'select-has-error' : ''"
                  >
                    <div class="select">
                      <select v-model="$v.status.$model">
                        <option :value="null">-- Choisissez --</option>
                        <option :value="1">En cours</option>
                        <option :value="0">Résolus</option>
                      </select>
                    </div>
                  </VControl>
                </VField>
              </div>

              <div class="column is-4">
                <VField>
                  <label>Date de mail récépicé</label>
                  <VControl icon="feather:calendar" :has-error="$v.date_mail.$invalid && $v.date_mail.$dirty">
                    <input v-model="$v.date_mail.$model" type="date" class="input" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-4">
                <VField>
                  <label>Date du courier récépicé</label>
                  <VControl
                    icon="feather:calendar"
                    :has-error="$v.date_courier.$invalid && $v.date_courier.$dirty"
                  >
                    <input v-model="$v.date_courier.$model" type="date" class="input" />
                  </VControl>
                </VField>
              </div>
            </div>
          </VLoader>
        </div>
      </div>
    </div>
  </form>
</template>
