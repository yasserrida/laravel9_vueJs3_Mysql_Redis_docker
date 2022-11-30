import { createApp as createClientApp, h, Suspense } from 'vue'
import { createHead, HeadClient } from '@vueuse/head'
import { createPinia } from 'pinia'
import { createI18n } from '/@src/i18n'
import { createRouter } from '/@src/router'
import { initDarkmode } from '/@src/stores/darkmode'
import { createApi } from '/@src/composable/useApi'
import { registerGlobalComponents, registerRouterNavigationGuards } from './app-custom'
import { AxiosInstance } from 'axios'
import VueroApp from '/@src/VueroApp.vue'
import '/@src/styles'

export type VueroAppContext = Awaited<ReturnType<typeof createApp>>

export async function createApp() {
  const head: HeadClient = createHead()
  const i18n = createI18n()
  const router = createRouter()
  const pinia = createPinia()
  const api: AxiosInstance = createApi()

  const app = createClientApp({
    setup() {
      initDarkmode()

      return () => {
        return h(Suspense, null, {
          default: () => h(VueroApp),
        })
      }
    },
  })

  app.mixin({ methods: { hasError: (state: any): any => state.$invalid && state.$dirty } })

  const vuero = {
    app,
    api,
    router,
    i18n,
    head,
    pinia,
  }

  await registerGlobalComponents(vuero)
  app.use(vuero.pinia)
  app.use(vuero.head)
  app.use(vuero.i18n)

  registerRouterNavigationGuards(vuero)
  app.use(vuero.router)

  return vuero
}
