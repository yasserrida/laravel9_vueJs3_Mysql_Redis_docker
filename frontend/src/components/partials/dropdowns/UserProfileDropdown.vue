<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useUserSession } from '/@src/stores/userSession'
import { logout } from '/@src/api/authApi'

const userSession = useUserSession()
const router = useRouter()

const handlLogout = async (): Promise<void> => {
  let res: boolean = await logout()
  if (res) {
    userSession.logoutUser()
    router.push('/auth/login')
  }
}
</script>

<template>
  <VDropdown right spaced class="user-dropdown profile-dropdown">
    <template #button="{ toggle }">
      <a
        tabindex="0"
        class="is-trigger dropdown-trigger"
        aria-haspopup="true"
        @keydown.space.prevent="toggle"
        @click="toggle"
      >
        <VAvatar picture="/images/avatars/placeholder.jpg" />
      </a>
    </template>

    <template #content>
      <div class="dropdown-head">
        <VAvatar size="large" picture="/images/avatars/placeholder.jpg" />

        <div class="meta">
          <span>{{ userSession.user ? userSession.user.name : '' }}</span>
          <span>{{ userSession.user ? userSession.user.role : '' }}</span>
        </div>
      </div>

      <a href="javascript:void(0)" role="menuitem" class="dropdown-item is-media" @click="router.push('/ticket')">
        <div class="icon">
          <i aria-hidden="true" class="lnil lnil-support"></i>
        </div>
        <div class="meta">
          <span>Support</span>
          <span>Ticket Support</span>
        </div>
      </a>

      <a href="javascript:void(0)" role="menuitem" class="dropdown-item is-media" @click="router.push('/profile')">
        <div class="icon">
          <i aria-hidden="true" class="lnil lnil-user-alt"></i>
        </div>
        <div class="meta">
          <span>Profile</span>
          <span>Voir ton profile</span>
        </div>
      </a>

      <hr class="dropdown-divider" />

      <div class="dropdown-item is-button">
        <VButton
          class="logout-button"
          icon="feather:log-out"
          color="primary"
          role="menuitem"
          raised
          fullwidth
          @click="handlLogout"
        >
          Se Déconnecter
        </VButton>
      </div>
    </template>
  </VDropdown>
</template>
