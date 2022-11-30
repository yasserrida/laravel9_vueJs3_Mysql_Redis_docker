import { defineAsyncComponent } from 'vue'
import { SetupCalendar } from 'v-calendar'
import { plugin as VueTippy } from 'vue-tippy'
import { RouteLocationNormalized, START_LOCATION } from 'vue-router'
import { useNotyf } from './composable/useNotyf'
import { useUserSession } from './stores/userSession'
import type { VueroAppContext } from './app'
import { getNotificationsUnreaded } from '/@src/api/notifApi'
import { useNotificationsState } from '/@src/stores/notificationsState'

export async function registerGlobalComponents({ app }: VueroAppContext): Promise<void> {
  const background = (await import('/@src/utils/background')).default
  const tooltip = (await import('/@src//utils/tooltip')).default

  app.use(SetupCalendar, {})
  app.use(VueTippy, {
    component: 'Tippy',
    defaultProps: {
      theme: 'light',
    },
  })

  app.component(
    // eslint-disable-next-line vue/multi-word-component-names
    'Multiselect',
    defineAsyncComponent({
      loader: () => import('@vueform/multiselect').then((mod: any): any => mod.default),
      delay: 0,
      suspensible: false,
    })
  )
  app.component(
    'VCalendar',
    defineAsyncComponent({
      loader: () => import('v-calendar').then((mod) => mod.Calendar),
      delay: 0,
      suspensible: false,
    })
  )
  app.component(
    'VDatePicker',
    defineAsyncComponent({
      loader: () => import('v-calendar').then((mod) => mod.DatePicker),
      delay: 0,
      suspensible: false,
    })
  )

  app.directive('background', background)
  app.directive('tooltip', tooltip)
}

export function registerRouterNavigationGuards({ router, api }: VueroAppContext): void {
  router.beforeEach(async (to: RouteLocationNormalized, from: RouteLocationNormalized) => {
    const userSession = useUserSession()
    const notificationsState: any = useNotificationsState()
    const notyf = useNotyf()

    if (to.path == '/') return { path: '/reclamation' }

    if (from === START_LOCATION && userSession.isLoggedIn) {
      try {
        const { data: user } = await api.get('/api/users/me')
        userSession.setUser(user)
        notificationsState.setNotifications(await getNotificationsUnreaded())

        if (to.meta.isAdmin) {
          if (user.role != 'ADMINISTRATEUR') return { path: '/reclamation' }
        } else if (to.meta.notAuth) {
          return { path: '/reclamation' }
        }
      } catch (err) {
        notyf.dismissAll()
        notyf.error({ message: 'Session invalide', duration: 6000 })
        userSession.logoutUser()
        if (to.meta.requiresAuth) {
          return { name: 'auth-login', query: { redirect: to.fullPath } }
        }
      }
    } else if (to.meta.requiresAuth && !userSession.isLoggedIn) {
      notyf.dismissAll()
      notyf.error({ message: 'Désolé, vous devez vous connecter pour accéder à cette section', duration: 6000 })

      return {
        name: 'auth-login',
        query: { redirect: to.fullPath },
      }
    }
  })
}
