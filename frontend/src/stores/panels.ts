import { acceptHMRUpdate, defineStore } from 'pinia'
import { RemovableRef, useStorage } from '@vueuse/core'

export type ActivePanelId = 'none' | 'search' | 'languages' | 'activity' | 'task'

export const usePanels = defineStore(
  'panels',
  (): { active: RemovableRef<any>; setActive: (panelId: ActivePanelId) => ActivePanelId; close: () => string } => {
    const active = useStorage<ActivePanelId>('active-panel', 'none')

    const setActive = (panelId: ActivePanelId): ActivePanelId => (active.value = panelId)
    const close = (): string => (active.value = 'none')

    return {
      active,
      setActive,
      close,
    } as const
  }
)

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(usePanels, import.meta.hot))
}
