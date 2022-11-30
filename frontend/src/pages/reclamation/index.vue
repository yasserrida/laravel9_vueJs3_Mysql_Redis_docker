<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { ref } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { can } from '/@src/utils/PermissionsHelper'
import MainHeader from '/@src/components/partials/MainHeader.vue'
import List from './Liste.vue'
import niveau1 from './Niveau1.vue'

useViewWrapper().setPageTitle('Réclamation')
useHead({ title: 'Réclamation' })

const tabIndex = ref(0)

const navs: Array<{ name: string; icon: string }> = can('RECLAMATION-CREATE')
  ? [
      { name: 'En cours', icon: 'feather:repeat' },
      { name: 'Résolus', icon: 'feather:check' },
      { name: 'Nouvelle réclamation', icon: 'feather:plus' },
    ]
  : [
      { name: 'En cours', icon: 'feather:repeat' },
      { name: 'Résolus', icon: 'feather:check' },
    ]

const changeTab = (val: number): void => {
  tabIndex.value = val
}
</script>

<template>
  <div class="is-navbar-lg">
    <MainHeader :tab-index="tabIndex" :show-profile="false" :navs="navs" @change-tab="changeTab" />

    <Transition name="slide-x" mode="out-in">
      <List v-if="tabIndex == 0" status="EnCours" />
      <List v-else-if="tabIndex == 1" status="Resolu" />
      <niveau1 v-else-if="tabIndex == 2" />
    </Transition>
  </div>
</template>
