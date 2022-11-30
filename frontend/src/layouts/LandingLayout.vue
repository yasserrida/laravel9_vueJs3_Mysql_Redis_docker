<script setup lang="ts">
export type LandingLayoutTheme = 'light' | 'darker'

const props = withDefaults(
  defineProps<{
    theme?: LandingLayoutTheme
  }>(),
  {
    theme: 'light',
  }
)
</script>

<template>
  <div class="minimal-wrapper" :class="[props.theme]">
    <slot></slot>
  </div>
</template>
