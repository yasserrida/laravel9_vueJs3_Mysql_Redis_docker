<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const isLoading = ref(false)

router.beforeEach((): void => {
  isLoading.value = true
})
router.afterEach((): void => {
  setTimeout((): void => {
    isLoading.value = false
  }, 200)
})
</script>

<template>
  <img class="image" src="/@src/assets/logo.png" width="211" height="30" alt="logo" />
</template>

<style lang="scss" scoped>
.image {
  width: 211px;
}
</style>
