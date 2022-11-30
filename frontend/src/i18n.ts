import { useStorage } from '@vueuse/core'
import { createI18n as createClientI18n } from 'vue-i18n'
import messages from '@intlify/vite-plugin-vue-i18n/messages'

export function createI18n() {
  const defaultLocale = useStorage('locale', navigator?.language || 'fr')
  const i18n = createClientI18n({
    locale: defaultLocale.value,
    messages,
  })

  return i18n
}
