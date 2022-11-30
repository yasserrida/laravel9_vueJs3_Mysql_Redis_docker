import type { Ref } from 'vue'
import { reactive, ref, watchEffect } from 'vue'
import { onClickOutside } from '@vueuse/core'

export function useDropdown(container: Ref<HTMLElement | undefined>) {
  const isOpen = ref(false)

  onClickOutside(container, (): void => {
    isOpen.value = false
  })

  const open = (): void => {
    isOpen.value = true
  }

  const close = (): void => {
    isOpen.value = false
  }

  const toggle = (): void => {
    isOpen.value = !isOpen.value
  }

  watchEffect((): void => {
    if (!container.value) {
      return
    }

    if (isOpen.value) {
      container.value.classList.add('is-active')
    } else {
      container.value.classList.remove('is-active')
    }
  })

  return reactive({
    isOpen,
    open,
    close,
    toggle,
  })
}
