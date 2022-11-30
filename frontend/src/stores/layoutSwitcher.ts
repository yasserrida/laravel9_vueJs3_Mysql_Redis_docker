import { computed, ref, defineAsyncComponent } from 'vue'
import { useRoute } from 'vue-router'
import { acceptHMRUpdate, defineStore } from 'pinia'

import type { SidebarTheme } from '/@src/components/navigation/desktop/Sidebar.vue'
import type { SideblockTheme } from '/@src/components/navigation/desktop/Sideblock.vue'

export const useLayoutSwitcher = defineStore('layoutSwitcher', () => {
  const route = useRoute()

  // utils
  const isNavbarRoute = computed((): boolean => route?.fullPath?.startsWith?.('/navbar/'))
  const isSidebarRoute = computed((): boolean => route?.fullPath?.startsWith?.('/sidebar/'))
  const hasDynamicLayout = computed((): boolean => isNavbarRoute.value || isSidebarRoute.value)
  const navbarLayoutLink = computed((): string => route?.fullPath?.replace?.('sidebar', 'navbar') ?? '')
  const sidebarLayoutLink = computed((): string => route?.fullPath?.replace?.('navbar', 'sidebar') ?? '')

  // navbar
  const NavbarLayout = defineAsyncComponent({
    loader: () => import('/@src/layouts/NavbarLayout.vue'),
    delay: 0,
    suspensible: false,
  })
  const NavbarDropdownLayout = defineAsyncComponent({
    loader: () => import('/@src/layouts/NavbarDropdownLayout.vue'),
    delay: 0,
    suspensible: false,
  })
  const NavbarSearchLayout = defineAsyncComponent({
    loader: () => import('/@src/layouts/NavbarSearchLayout.vue'),
    delay: 0,
    suspensible: false,
  })

  const navbarComponents = {
    'navbar-default': NavbarLayout,
    'navbar-fade': NavbarLayout,
    'navbar-colored': NavbarLayout,

    'navbar-dropdown': NavbarDropdownLayout,
    'navbar-dropdown-colored': NavbarDropdownLayout,
    'navbar-clean': NavbarSearchLayout,
    'navbar-clean-center': NavbarSearchLayout,
    'navbar-clean-fade': NavbarSearchLayout,
  } as const

  type NavbarComponentsId = keyof typeof navbarComponents
  const navbarComponentsIds: string[] = Object.keys(navbarComponents)

  const navbarLayoutId = ref<NavbarComponentsId>('navbar-default')
  const navbarLayoutComponent = computed((): any => {
    return navbarComponents[navbarLayoutId.value] || NavbarLayout
  })

  const navbarLayoutTheme = computed(() => {
    switch (navbarLayoutId.value) {
      case 'navbar-fade':
      case 'navbar-clean-fade':
        return 'fade'
      case 'navbar-colored':
      case 'navbar-dropdown-colored':
        return 'colored'
      case 'navbar-clean-center':
        return 'center'
      default:
        return 'default'
    }
  })

  // sidebar
  const SidebarLayout = defineAsyncComponent({
    loader: () => import('/@src/layouts/SidebarLayout.vue'),
    delay: 0,
    suspensible: false,
  })
  const SideblockLayout = defineAsyncComponent({
    loader: () => import('/@src/layouts/SideblockLayout.vue'),
    delay: 0,
    suspensible: false,
  })

  const sidebarComponents = {
    'sidebar-default': SidebarLayout,
    'sidebar-color': SidebarLayout,
    'sidebar-color-curved': SidebarLayout,
    'sidebar-curved': SidebarLayout,
    'sidebar-float': SidebarLayout,
    'sidebar-labels': SidebarLayout,
    'sidebar-labels-hover': SidebarLayout,

    'sideblock-default': SideblockLayout,
    'sideblock-color': SideblockLayout,
    'sideblock-color-curved': SideblockLayout,
    'sideblock-curved': SideblockLayout,
  } as const

  type SidebarComponentsId = keyof typeof sidebarComponents
  const sidebarComponentsIds = Object.keys(sidebarComponents)

  const sidebarLayoutId = ref<SidebarComponentsId>('sidebar-default')
  const sidebarLayoutComponent = computed((): any => {
    return sidebarComponents[sidebarLayoutId.value] || SidebarLayout
  })

  const sidebarLayoutTheme = computed<SidebarTheme | SideblockTheme>(() => {
    switch (sidebarLayoutId.value) {
      case 'sidebar-float':
        return 'float'
      case 'sidebar-labels':
        return 'labels'
      case 'sidebar-labels-hover':
        return 'labels-hover'
      case 'sidebar-color':
      case 'sideblock-color':
        return 'color'
      case 'sidebar-curved':
      case 'sideblock-curved':
        return 'curved'
      case 'sideblock-color-curved':
      case 'sidebar-color-curved':
        return 'color-curved'
      case 'sidebar-default':
      case 'sideblock-default':
      default:
        return 'default'
    }
  })

  // dynamic layout
  const dynamicLayoutId = computed<NavbarComponentsId | SidebarComponentsId>({
    get: () => {
      if (isNavbarRoute.value) {
        return navbarLayoutId.value
      } else {
        return sidebarLayoutId.value
      }
    },
    set: (value): void => {
      if (navbarComponentsIds.includes(value)) {
        navbarLayoutId.value = value as NavbarComponentsId
        return
      }

      if (sidebarComponentsIds.includes(value)) {
        sidebarLayoutId.value = value as SidebarComponentsId
        return
      }
    },
  })

  const dynamicLayoutComponent = computed((): any => {
    if (isNavbarRoute.value) {
      return navbarLayoutComponent.value
    } else {
      return sidebarLayoutComponent.value
    }
  })

  const dynamicLayoutProps = computed(() => {
    if (isNavbarRoute.value) {
      return {
        theme: navbarLayoutTheme.value,
        key: navbarLayoutId.value,
      }
    } else {
      return {
        theme: sidebarLayoutTheme.value,
        key: sidebarLayoutId.value,
      }
    }
  })

  function setDynamicLayoutId(theme: NavbarComponentsId | SidebarComponentsId): void {
    dynamicLayoutId.value = theme
  }

  return {
    dynamicLayoutComponent,
    dynamicLayoutProps,
    dynamicLayoutId,
    setDynamicLayoutId,
    sidebarLayoutId,
    sidebarLayoutComponent,
    sidebarLayoutTheme,
    navbarLayoutId,
    navbarLayoutComponent,
    navbarLayoutTheme,
    isNavbarRoute,
    isSidebarRoute,
    navbarLayoutLink,
    sidebarLayoutLink,
    hasDynamicLayout,
  } as const
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useLayoutSwitcher, import.meta.hot))
}
