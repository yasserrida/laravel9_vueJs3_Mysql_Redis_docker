import { acceptHMRUpdate, defineStore } from 'pinia'
import { ref, computed, Ref, ComputedRef } from 'vue'
import { RemovableRef, useStorage } from '@vueuse/core'
import { disconnectSocket } from '/@src/composable/useSocket'

export type UserData = Record<string, any> | null

export const useUserSession = defineStore(
  'userSession',
  (): {
    token: RemovableRef<string | undefined>
    user: Ref<Partial<UserData> | undefined>
    permissions: Ref<Array<any>>
    isLoggedIn: ComputedRef<boolean>
    getRole: ComputedRef<string>
    getPermissions: ComputedRef<any[]>
    setUser: (newUser: Partial<UserData>) => void
    setToken: (newToken: string) => void
    logoutUser: () => Promise<void>
  } => {
    const token = useStorage('token', '')
    const user = ref<Partial<UserData>>()
    const permissions = ref<Array<any>>([])
    const isLoggedIn = computed((): boolean => token.value !== undefined && token.value !== '')
    const getRole = computed((): string => (user.value ? (user.value.role as string) : ''))
    const getPermissions = computed((): any[] => (user.value ? permissions.value : []))

    const setUser = (newUser: Partial<UserData>): void => {
      permissions.value = newUser?.permissions
      delete newUser?.permissions
      user.value = newUser
    }
    const setToken = (newToken: string): void => {
      token.value = newToken
    }
    const logoutUser = async (): Promise<void> => {
      token.value = undefined
      user.value = undefined
      permissions.value = []
      disconnectSocket()
      localStorage.clear()
    }

    return {
      token,
      user,
      permissions,
      isLoggedIn,
      getRole,
      getPermissions,
      setUser,
      setToken,
      logoutUser,
    } as const
  }
)

if (import.meta.hot) import.meta.hot.accept(acceptHMRUpdate(useUserSession, import.meta.hot))
