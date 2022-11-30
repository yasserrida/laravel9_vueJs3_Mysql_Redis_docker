<route lang="yaml">
meta:
  requiresAuth: true
  isAdmin: true
</route>

<script setup lang="ts">
import { useRoute } from 'vue-router'
const route = useRoute()
</script>

<template>
  <AppLayout>
    <RouterView v-slot="{ Component }">
      <Transition name="fade-fast" mode="out-in">
        <component :is="Component" :key="route.fullPath" />
      </Transition>
    </RouterView>
  </AppLayout>
</template>

<style lang="scss">
@import './../scss/app.scss';
</style>
