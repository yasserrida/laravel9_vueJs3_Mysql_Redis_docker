<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { ref } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import MainHeader from '/@src/components/partials/MainHeader.vue'
import Fournisseurs from '/@src/pages/gestion/Fournisseurs.vue'
import Gammes from '/@src/pages/gestion/Gammes.vue'
import Produits from '/@src/pages/gestion/Produits.vue'

useViewWrapper().setPageTitle('Gestion')
useHead({ title: 'Gestion' })

const navs: Array<{ name: string; icon: string }> = [
  { name: 'Fournisseurs', icon: 'feather:archive' },
  { name: 'Gammes', icon: 'feather:list' },
  { name: 'Produits', icon: 'feather:database' },
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
      <Fournisseurs v-if="tabIndex == 0" />
      <Gammes v-else-if="tabIndex == 1" />
      <Produits v-else-if="tabIndex == 2" />
    </Transition>
  </div>
</template>
