<script setup lang="ts">
import { onMounted } from 'vue'
import { useSocket, disconnectSocket } from '/@src/composable/useSocket'
import { useUserSession } from '/@src/stores/userSession'
import { useNotificationsState } from '/@src/stores/notificationsState'

const socket: any = useSocket()
const userSession = useUserSession()
const notificationsState = useNotificationsState()

const initSocket = (): void => {
  if (socket && userSession.user && userSession.user.id) {
    socket.channel('yasser_private-App.Models.User.' + userSession.user.id).notification((e: any): void => {
      notificationsState.pushNotifcation(e)
    })
  } else {
    disconnectSocket()
  }
}

onMounted((): void => {
  initSocket()
})
</script>

<template>
  <RouterView v-slot="{ Component }">
    <Transition name="fade-slow" mode="out-in">
      <component :is="Component" />
    </Transition>
  </RouterView>
  <VReloadPrompt app-name="yasserApp" />
</template>

<style lang="scss">
.select-has-error select,
.select-has-error select:focus {
  border-color: var(--danger) !important;
  box-shadow: var(--light-box-shadow);
}

.select-has-error svg {
  color: var(--danger) !important;
}
</style>
