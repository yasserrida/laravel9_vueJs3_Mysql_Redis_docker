import { acceptHMRUpdate, defineStore } from 'pinia'
import { computed, ComputedRef } from 'vue'
import { RemovableRef, useStorage } from '@vueuse/core'

export const useNotificationsState = defineStore(
  'useNotificationsState',
  (): {
    notifications: RemovableRef<any[]>
    newNotif: ComputedRef<number>
    setNotifications: (value: any) => void
    pushNotifcation: ({ text, route, created_at }: any) => void
  } => {
    const notifications = useStorage<Array<any>>('notificationsArray', [])
    const newNotif = computed((): number => notifications.value.length)

    const setNotifications = (value: any): void => {
      notifications.value = value
    }
    const pushNotifcation = ({ text, route, created_at }: any): void => {
      notifications.value.unshift({ data: { text, route }, created_at })
    }

    return {
      notifications,
      newNotif,
      setNotifications,
      pushNotifcation,
    }
  }
)

if (import.meta.hot) import.meta.hot.accept(acceptHMRUpdate(useNotificationsState, import.meta.hot))
