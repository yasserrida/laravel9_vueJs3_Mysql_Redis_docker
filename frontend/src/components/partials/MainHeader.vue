<script setup lang="ts">
import { useUserSession } from '/@src/stores/userSession'

const userSession = useUserSession()

const props = defineProps({
  tabIndex: { type: Number, default: 1 },
  showProfile: { type: Boolean, default: false },
  navs: { type: Array<{ name: string; icon: string }>, default: () => [] },
})

defineEmits(['changeTab'])
</script>

<template>
  <div>
    <div v-if="props.showProfile" class="profile-header has-text-centered">
      <VAvatar size="xl" picture="/images/avatars/placeholder.jpg" />
      <h3 class="title is-4 is-narrow">{{ userSession.user?.name }}</h3>
      <p class="light-text">{{ userSession.user?.email }}</p>
      <div class="profile-stats"></div>
    </div>

    <div class="profile-body my-4">
      <div class="settings-section">
        <button
          v-for="(item, index) in props.navs"
          :key="index"
          class="settings-box"
          :class="tabIndex == index ? 'is-active' : ''"
          @click="$emit('changeTab', index)"
        >
          <VIconWrap size="large" dark="1" color="white" :icon="(item as any).icon" />
          <h3>{{ (item as any).name }}</h3>
        </button>
      </div>
    </div>
  </div>
</template>
