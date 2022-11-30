import { createApp } from './app'
import * as NProgress from 'nprogress'

createApp().then(async (vuero): Promise<void> => {
  vuero.router.beforeEach((): void => {
    NProgress.start()
  })
  vuero.router.afterEach((): void => {
    NProgress.done()
  })

  await vuero.router.isReady()

  vuero.app.mount('#app')
})
