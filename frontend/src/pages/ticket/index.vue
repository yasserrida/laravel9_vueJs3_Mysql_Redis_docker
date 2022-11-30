<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { ref } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import MainHeader from '/@src/components/partials/MainHeader.vue'
import Liste from './Liste.vue'
import New from './New.vue'

useViewWrapper().setPageTitle('Ticket Support')
useHead({ title: 'Ticket Support' })

const navs: Array<{ name: string; icon: string }> = [
  { name: 'Liste Tickets', icon: 'feather:list' },
  { name: 'Nouvelle Ticket', icon: 'feather:plus' },
]

const tabIndex = ref(0)
const changeTab = (val: number): void => {
  tabIndex.value = val
}
</script>

<template>
  <div class="is-navbar-lg">
    <MainHeader :tab-index="tabIndex" :show-profile="false" :navs="navs" @change-tab="changeTab" />

    <Transition name="slide-x" mode="out-in">
      <Liste v-if="tabIndex == 0" />
      <New v-else-if="tabIndex == 1" />
    </Transition>
  </div>
</template>
