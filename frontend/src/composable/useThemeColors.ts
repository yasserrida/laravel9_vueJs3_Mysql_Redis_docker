import { computed, reactive } from 'vue'
import { createSharedComposable, useCssVar } from '@vueuse/core'
import { HSLToHex } from '/@src/utils/color-converter'

export const useThemeColors = createSharedComposable(() => {
  const primary = useCssVar('--primary', document.documentElement)
  const success = useCssVar('--success', document.documentElement)
  const info = useCssVar('--info', document.documentElement)
  const warning = useCssVar('--warning', document.documentElement)
  const danger = useCssVar('--danger', document.documentElement)
  const purple = useCssVar('--purple', document.documentElement)
  const blue = useCssVar('--blue', document.documentElement)
  const green = useCssVar('--green', document.documentElement)
  const yellow = useCssVar('--yellow', document.documentElement)
  const orange = useCssVar('--orange', document.documentElement)

  const themeColors = reactive({
    primary: computed((): string => HSLToHex(primary.value)),
    primaryMedium: '#b4e4ce',
    primaryLight: '#f7fcfa',
    secondary: '#ff227d',
    accent: '#797bf2',
    accentMedium: '#d4b3ff',
    accentLight: '#b8ccff',
    success: computed((): string => HSLToHex(success.value)),
    info: computed((): string => HSLToHex(info.value)),
    warning: computed((): string => HSLToHex(warning.value)),
    danger: computed((): string => HSLToHex(danger.value)),
    purple: computed((): string => HSLToHex(purple.value)),
    blue: computed((): string => HSLToHex(blue.value)),
    green: computed((): string => HSLToHex(green.value)),
    yellow: computed((): string => HSLToHex(yellow.value)),
    orange: computed((): string => HSLToHex(orange.value)),
    lightText: '#a2a5b9',
    fadeGrey: '#ededed',
  } as const)

  return themeColors
})
