import { computed, watchEffect, WritableComputedRef } from 'vue'
import { usePreferredDark, useStorage } from '@vueuse/core'
import { acceptHMRUpdate, defineStore } from 'pinia'

export const DARK_MODE_BODY_CLASS = 'is-dark'
export type DarkModeSchema = 'auto' | 'dark' | 'light'

export const useDarkmode = defineStore(
  'darkmode',
  (): { isDark: WritableComputedRef<boolean>; onChange: (event: Event) => void } => {
    const preferredDark = usePreferredDark()
    const colorSchema = useStorage<DarkModeSchema>('color-schema', 'auto')

    const isDark = computed({
      get(): boolean {
        return colorSchema.value === 'auto' ? preferredDark.value : colorSchema.value === 'dark'
      },
      set(v: boolean): void {
        if (v === preferredDark.value) colorSchema.value = 'auto'
        else colorSchema.value = v ? 'dark' : 'light'
      },
    })

    const onChange = (event: Event): void => {
      const target = event.target as HTMLInputElement
      isDark.value = !target.checked
    }

    return {
      isDark,
      onChange,
    }
  }
)

export const initDarkmode = (): void => {
  const darkmode = useDarkmode()

  watchEffect((): void => {
    const body = document.documentElement

    if (darkmode.isDark) {
      body.classList.add(DARK_MODE_BODY_CLASS)
    } else {
      body.classList.remove(DARK_MODE_BODY_CLASS)
    }
  })
}

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useDarkmode, import.meta.hot))
}
