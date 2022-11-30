import { Ref, ref } from 'vue'
import { acceptHMRUpdate, defineStore } from 'pinia'

export type SidebarId = 'none' | 'messages' | 'layouts' | 'home' | 'components' | 'elements'

export const useSidebar = defineStore(
  'sidebar',
  (): {
    active: Ref<any>
    toggle: (sidebarId: SidebarId) => SidebarId
    setActive: (sidebarId: SidebarId) => SidebarId
    close: () => string
  } => {
    const active = ref<SidebarId>('none')

    const toggle = (sidebarId: SidebarId): SidebarId =>
      (active.value = active.value === sidebarId ? 'none' : sidebarId)
    const setActive = (sidebarId: SidebarId): SidebarId => (active.value = sidebarId)
    const close = (): string => (active.value = 'none')

    return {
      active,
      toggle,
      setActive,
      close,
    } as const
  }
)

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useSidebar, import.meta.hot))
}
