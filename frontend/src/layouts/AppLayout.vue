<script setup lang="ts">
import { ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { hasRoles, can } from '/@src/utils/PermissionsHelper'

export type NavbarTheme = 'default' | 'colored' | 'fade'
export type SubnavId = 'closed' | 'reclamation' | 'notification' | 'recherche' | 'search' | 'users' | 'gestion'

const props = withDefaults(
  defineProps<{
    theme?: NavbarTheme
    nowrap?: boolean
  }>(),
  { theme: 'default' }
)

const viewWrapper = useViewWrapper()
const route = useRoute()
const router = useRouter()
const isMobileSidebarOpen = ref(false)
const isMobileSideblockOpen = ref(false)
const isDesktopSideblockOpen = ref(false)
const activeMobileSubsidebar = ref<SubnavId>('reclamation')
const activeSubnav = ref<SubnavId>('reclamation')
const showSearchBar = ref<boolean>(false)
const filter = ref('')
const ItemsArray = ref<Array<any>>([])

const getData = async (): Promise<void> => {
  if (!filter.value) ItemsArray.value = []
}

const handlClick = (item: any): void => {
  filter.value = item.nom + ' ' + (item.prenom || '')
}

const toggleSubnav = (subnav: SubnavId): void => {
  activeSubnav.value = subnav
  activeMobileSubsidebar.value = subnav
  isMobileSideblockOpen.value = false
  router.push(subnav)
}

const clickSearch = (): void => {
  ItemsArray.value = []
  showSearchBar.value = !showSearchBar.value
}

watch(
  (): string => route.fullPath,
  (): void => {
    isMobileSidebarOpen.value = false
  }
)
</script>

<template>
  <div class="sidebar-layout">
    <div class="app-overlay"></div>

    <!-- Mobile navigation -->
    <MobileNavbar :is-open="isMobileSideblockOpen" @toggle="isMobileSideblockOpen = !isMobileSideblockOpen">
      <template #brand>
        <RouterLink :to="{ name: 'reclamation' }" class="navbar-item is-brand">
          <AnimatedLogo width="38px" height="38px" />
        </RouterLink>
        <div class="brand-end">
          <UserProfileDropdown />
        </div>
      </template>
    </MobileNavbar>

    <!-- Mobile sidebar links -->
    <MobileSidebar :is-open="isMobileSideblockOpen" @toggle="isMobileSideblockOpen = !isMobileSideblockOpen">
      <template #links>
        <li
          v-if="can('RECLAMATION-CREATE') || can('RECLAMATION-UPDATE')"
          :class="
            (activeMobileSubsidebar === 'reclamation' && route.path.startsWith('/reclamation')) ||
            route.path.startsWith('/reclamation')
              ? 'is-active'
              : ''
          "
        >
          <a
            aria-label="Display content"
            tabindex="0"
            @keydown.space.prevent="toggleSubnav('reclamation')"
            @click="toggleSubnav('reclamation')"
          >
            <i aria-hidden="true" class="iconify" data-icon="fluent:document-error-24-regular"></i>
            <span>Réclamantion</span>
          </a>
        </li>
        <li
          v-if="hasRoles(['RESPONSABLE', 'GESTIONNAIRE'])"
          :class="
            (activeMobileSubsidebar === 'notification' && route.path.startsWith('/notification')) ||
            route.path.startsWith('/notification')
              ? 'is-active'
              : ''
          "
        >
          <a
            aria-label="Display components content"
            tabindex="0"
            @keydown.space.prevent="toggleSubnav('notification')"
            @click="toggleSubnav('notification')"
          >
            <i aria-hidden="true" class="iconify" data-icon="mdi:bell-outline"></i>
            <span>Notification</span>
          </a>
        </li>
        <li
          v-if="hasRoles(['RESPONSABLE'])"
          :class="
            (activeMobileSubsidebar === 'users' && route.path.startsWith('/users')) ||
            route.path.startsWith('/users')
              ? 'is-active'
              : ''
          "
        >
          <a
            aria-label="Display components content"
            tabindex="0"
            @keydown.space.prevent="toggleSubnav('users')"
            @click="toggleSubnav('users')"
          >
            <i aria-hidden="true" class="iconify" data-icon="mdi:bell-outline"></i>
            <span>Utilisateurs</span>
          </a>
        </li>
        <li
          v-if="hasRoles(['RESPONSABLE'])"
          :class="
            (activeMobileSubsidebar === 'gestion' && route.path.startsWith('/gestion')) ||
            route.path.startsWith('/gestion')
              ? 'is-active'
              : ''
          "
        >
          <a
            aria-label="Display components content"
            tabindex="0"
            @keydown.space.prevent="toggleSubnav('gestion')"
            @click="toggleSubnav('gestion')"
          >
            <i aria-hidden="true" class="iconify" data-icon="mdi:bell-outline"></i>
            <span>Gestion</span>
          </a>
        </li>
      </template>

      <template #bottom-links>
        <!--<li>
          <a aria-label="Display search panel" tabindex="0" @keydown.space.prevent="panels.setActive('search')"
            @click="panels.setActive('search')">
            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
          </a>
        </li>
        <li>
          <a aria-label="View settings" href="#">
            <i aria-hidden="true" class="iconify" data-icon="feather:settings"></i>
          </a>
        </li>-->
      </template>
    </MobileSidebar>

    <!-- Desktop navigation -->
    <Navbar>
      <template #title>
        <RouterLink :to="{ name: 'reclamation' }" class="brand">
          <AnimatedLogo width="38px" height="38px" />
        </RouterLink>
      </template>

      <template #toolbar>
        <Toolbar class="desktop-toolbar">
          <template #left>
            <div v-if="can('PERSONNE-CHECK')" class="toolbar-link" @click="clickSearch">
              <label tabindex="0" class="mx-auto mt-2">
                <i
                  aria-hidden="true"
                  class="iconify"
                  data-icon="feather:search"
                  style="width: 1.5em; height: 4em; cursor: pointer"
                ></i>
              </label>
            </div>
            <ToolbarNotification />
          </template>
          <template #right>
            <!-- <a class="toolbar-link right-panel-trigger" tabindex="0" @keydown.space.prevent="panels.setActive('activity')" @click="panels.setActive('activity')">
              <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
            </a> -->
          </template>
        </Toolbar>
        <!-- <LayoutSwitcher /> -->
        <UserProfileDropdown right />
      </template>

      <!-- Custom navbar links -->
      <template #links>
        <div class="centered-links" :class="showSearchBar ? 'is-hidden' : ''">
          <a
            v-if="can('RECLAMATION-CREATE') || can('RECLAMATION-UPDATE') || hasRoles(['RESPONSABLE'])"
            class="centered-link centered-link-toggle"
            tabindex="0"
            :class="
              (activeSubnav === 'reclamation' && route.path.startsWith('/reclamation')) ||
              route.path.startsWith('/reclamation')
                ? 'is-active'
                : ''
            "
            @keydown.space.prevent="toggleSubnav('reclamation')"
            @click="toggleSubnav('reclamation')"
          >
            <i aria-hidden="true" class="iconify" data-icon="fluent:document-error-24-regular"></i>
            <span>Réclamantion</span>
          </a>
          <a
            v-if="can('NOTIFICATION-CREATE') || can('NOTIFICATION-UPDATE') || hasRoles(['RESPONSABLE'])"
            class="centered-link centered-link-toggle"
            tabindex="0"
            :class="
              (activeSubnav === 'notification' && route.path.startsWith('/notification')) ||
              route.path.startsWith('/notification')
                ? 'is-active'
                : ''
            "
            @keydown.space.prevent="toggleSubnav('notification')"
            @click="toggleSubnav('notification')"
          >
            <i aria-hidden="true" class="iconify" data-icon="mdi:bell-outline"></i>
            <span>Notification</span>
          </a>
          <a
            v-if="hasRoles(['RESPONSABLE'])"
            class="centered-link centered-link-toggle"
            tabindex="0"
            :class="
              (activeSubnav === 'users' && route.path.startsWith('/users')) || route.path.startsWith('/users')
                ? 'is-active'
                : ''
            "
            @keydown.space.prevent="toggleSubnav('users')"
            @click="toggleSubnav('users')"
          >
            <i aria-hidden="true" class="iconify" data-icon="mdi:users"></i>
            <span>Utilisateurs</span>
          </a>
          <a
            v-if="hasRoles(['RESPONSABLE'])"
            class="centered-link centered-link-toggle"
            tabindex="0"
            :class="
              (activeSubnav === 'gestion' && route.path.startsWith('/gestion')) ||
              route.path.startsWith('/gestion')
                ? 'is-active'
                : ''
            "
            @keydown.space.prevent="toggleSubnav('gestion')"
            @click="toggleSubnav('gestion')"
          >
            <i aria-hidden="true" class="iconify" data-icon="mdi:cog"></i>
            <span>Gestion</span>
          </a>
        </div>

        <div class="centered-search" :class="!showSearchBar ? 'is-hidden' : ''">
          <div class="field">
            <div class="control has-icon">
              <input
                v-model="filter"
                type="text"
                class="input is-rounded search-input"
                placeholder="Rechercher"
                autocomplete="off"
                @keyup="getData"
              />
              <div class="form-icon">
                <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
              </div>
              <div
                class="form-icon is-right"
                tabindex="0"
                @keydown.space.prevent="showSearchBar = false"
                @click="showSearchBar = false"
              >
                <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
              </div>
              <div v-if="ItemsArray.length > 0" class="search-results has-slimscroll is-active">
                <div
                  v-for="(item, index) in ItemsArray"
                  :key="index"
                  class="search-result"
                  @click="handlClick(item)"
                >
                  <div class="meta">
                    <span>{{ item }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </Navbar>

    <!-- <LanguagesPanel />
    <ActivityPanel />
    <SearchPanel />
    <TaskPanel /> -->

    <VViewWrapper top-nav>
      <VPageContentWrapper>
        <template v-if="props.nowrap">
          <slot></slot>
        </template>
        <VPageContent v-else class="is-relative">
          <div class="page-title has-text-centered">
            <!-- Sidebar Trigger -->
            <div
              class="vuero-hamburger nav-trigger push-resize"
              tabindex="0"
              @keydown.space.prevent="isDesktopSideblockOpen = !isDesktopSideblockOpen"
              @click="isDesktopSideblockOpen = !isDesktopSideblockOpen"
            >
              <span class="menu-toggle has-chevron">
                <span :class="[isDesktopSideblockOpen && 'active']" class="icon-box-toggle">
                  <span class="rotate">
                    <i aria-hidden="true" class="icon-line-top"></i>
                    <i aria-hidden="true" class="icon-line-center"></i>
                    <i aria-hidden="true" class="icon-line-bottom"></i>
                  </span>
                </span>
              </span>
            </div>

            <div class="title-wrap">
              <h1 class="title is-4">{{ viewWrapper.pageTitle }}</h1>
            </div>

            <Toolbar class="desktop-toolbar">
              <template #left>
                <div class="toolbar-link" @click="showSearchBar = true">
                  <label tabindex="0" class="ml-auto">
                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                  </label>
                </div>
              </template>

              <template #right>
                <ToolbarNotification />

                <!--  <a class="toolbar-link right-panel-trigger" aria-label="View activity panel" tabindex="0"
                  @keydown.space.prevent="panels.setActive('activity')" @click="panels.setActive('activity')">
                  <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
                </a>-->
              </template>
            </Toolbar>
          </div>

          <slot></slot>
        </VPageContent>
      </VPageContentWrapper>
    </VViewWrapper>
  </div>
</template>
