<script setup lang="ts">
import { ref } from 'vue'
import { useDropdown } from '/@src/composable/useDropdown'
import { markNotifications, getNotificationsUnreaded } from '/@src/api/notifApi'
import { useNotificationsState } from '/@src/stores/notificationsState'
import { useRouter } from 'vue-router'
import dayjs from 'dayjs'
dayjs.locale('fr')

const notificationsState = useNotificationsState()
const dropdownElement = ref<HTMLElement>()
const dropdown = useDropdown(dropdownElement)
const router = useRouter()

const handleClick = async (): Promise<void> => {
  dropdown.toggle()
}

const ClearNotif = async (): Promise<void> => {
  await markNotifications()
  notificationsState.setNotifications(await getNotificationsUnreaded())
  dropdown.toggle()
}

const clickNotif = (route: string): void => {
  if (route && route.length) router.push(route)
}
</script>

<template>
  <div class="toolbar-notifications is-hidden-mobile">
    <div ref="dropdownElement" class="dropdown is-spaced is-right is-dots dropdown-trigger">
      <div
        tabindex="0"
        class="is-trigger"
        aria-haspopup="true"
        @click="handleClick"
        @keydown.space.prevent="handleClick"
      >
        <i aria-hidden="true" class="iconify" data-icon="feather:bell"></i>
        <span :class="notificationsState.newNotif ? 'new-indicator pulsate' : ''"></span>
      </div>
      <div class="dropdown-menu" role="menu">
        <div class="dropdown-content">
          <div class="heading">
            <div class="heading-left">
              <h6 class="heading-title">Notiffications</h6>
            </div>
            <div class="heading-right">
              <a v-if="notificationsState.notifications.length" class="notification-link" @click="ClearNotif"
                >Marquer Lu</a
              >
            </div>
          </div>
          <ul class="notification-list">
            <li v-for="(notif, index) in notificationsState.notifications" :key="index">
              <a class="notification-item" @click="clickNotif(notif.data.route)">
                <div class="user-content">
                  <p class="user-info">{{ notif.data.text }}</p>
                  <p class="time">{{ dayjs(notif.created_at).format('HH:mm | DD MMMM') }}</p>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
