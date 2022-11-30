<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useDarkmode } from '/@src/stores/darkmode'
import { useUserSession } from '/@src/stores/userSession'
import { useNotyf } from '/@src/composable/useNotyf'
import { login, me } from '/@src/api/authApi'
import { useSocket } from '/@src/composable/useSocket'

const socket: any = useSocket()
const darkmode = useDarkmode()
const router = useRouter()
const route = useRoute()
const notif = useNotyf()
const userSession = useUserSession()
const redirect = route.query.redirect as string
const isLoading = ref<boolean>(false)
const email = ref<string>('')
const password = ref<string>('')

const handleLogin = async (): Promise<void> => {
  isLoading.value = true
  const data: any = await login({ email: email.value, password: password.value })
  if (data) {
    userSession.setToken(data.access_token)
    const user: any = await me()
    if (user) {
      userSession.setUser(user)
      notif.dismissAll()
      notif.success(`Bienvenue ${user.name}`)
      if (redirect && redirect != '/') router.push(redirect)
      else router.push('/reclamation')
      if (!socket) {
        window.location.reload()
      }
    }
  }
  isLoading.value = false
}

useHead({ title: 'Login' })
</script>
<template>
  <div class="auth-wrapper-inner columns is-gapless">
    <!-- Form section -->
    <div class="column is-6">
      <div class="hero is-fullheight is-white">
        <div class="hero-heading">
          <label
            class="dark-mode ml-auto"
            tabindex="0"
            @keydown.space.prevent="(e: KeyboardEvent): void => (e.target as HTMLLabelElement).click()"
          >
            <input type="checkbox" :checked="!darkmode.isDark" @change="darkmode.onChange" />
            <span></span>
          </label>
          <div class="auth-logo">
            <RouterLink :to="{ name: 'index' }">
              <AnimatedLogo width="36px" height="36px" />
            </RouterLink>
          </div>
        </div>
        <div class="hero-body">
          <div class="container">
            <div class="columns">
              <div class="column is-12">
                <div class="auth-content">
                  <h2>VOTRE ESPACE</h2>
                </div>
                <div class="auth-form-wrapper">
                  <!-- Login Form -->
                  <div>
                    <div class="login-form">
                      <!-- Username -->
                      <VField>
                        <VControl icon="feather:user">
                          <input
                            v-model="email"
                            class="input"
                            type="text"
                            placeholder="Utilisateur"
                            autocomplete="username"
                          />
                        </VControl>
                      </VField>

                      <!-- Password -->
                      <VField>
                        <VControl icon="feather:lock">
                          <input
                            v-model="password"
                            class="input"
                            type="password"
                            placeholder="Password"
                            autocomplete="current-password"
                            @keyup.enter="handleLogin"
                          />
                        </VControl>
                      </VField>

                      <!-- Submit -->
                      <VControl class="login">
                        <VButton :loading="isLoading" color="primary" bold fullwidth raised @click="handleLogin">
                          Connection</VButton
                        >
                      </VControl>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Image section (hidden on mobile) -->
    <div class="column login-column is-6 h-hidden-mobile h-hidden-tablet-p hero-banner">
      <div class="hero login-hero is-fullheight is-app-grey">
        <div class="hero-body">
          <div class="columns">
            <div class="column is-10 is-offset-1">
              <img
                class="light-image has-light-shadow has-light-border"
                src="/@src/assets/logo.png"
                alt=""
              />
              <img class="dark-image has-light-shadow" src="/@src/assets/logo.png" alt="" />
            </div>
          </div>
        </div>
        <div class="hero-footer">
          <p class="has-text-centered"></p>
        </div>
      </div>
    </div>
  </div>
</template>
