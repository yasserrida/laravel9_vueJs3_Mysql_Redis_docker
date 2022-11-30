<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { ref } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import MainHeader from '/@src/components/partials/MainHeader.vue'
import Liste from './Liste.vue'
import New from './New.vue'
import { can } from '/@src/utils/PermissionsHelper'

useViewWrapper().setPageTitle('Notification')
useHead({ title: 'Notification' })

const navs: Array<{ name: string; icon: string }> = can('NOTIFICATION-CREATE')
  ? [
      { name: 'Liste', icon: 'feather:list' },
      { name: 'Nouvelle notification', icon: 'feather:plus' },
    ]
  : [{ name: 'Liste', icon: 'feather:list' }]

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
