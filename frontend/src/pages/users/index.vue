<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { ref } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import MainHeader from '/@src/components/partials/MainHeader.vue'
import Liste from './Liste.vue'
import New from './New.vue'

useViewWrapper().setPageTitle('Notification')
useHead({ title: 'Utilisateurs' })

const navs: Array<{ name: string; icon: string }> = [
  { name: 'Liste', icon: 'feather:list' },
  { name: 'Nouveau utilisateur', icon: 'feather:plus' },
]

const tabIndex = ref<number>(0)
const changeTab = (val: number): void => {
  tabIndex.value = val
}
</script>

<template>
  <div class="is-navbar-lg">
    <MainHeader :tab-index="tabIndex" :show-profile="true" :navs="navs" @change-tab="changeTab" />

    <Transition name="slide-x" mode="out-in">
      <Liste v-if="tabIndex == 0" />
      <New v-else-if="tabIndex == 1" />
    </Transition>
  </div>
</template>
