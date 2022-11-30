import { Ref, ref } from 'vue'
import { acceptHMRUpdate, defineStore } from 'pinia'

export const useViewWrapper = defineStore(
  'viewWrapper',
  (): {
    isPushed: Ref<boolean>
    isPushedBlock: Ref<boolean>
    pageTitle: Ref<string>
    setPushed: (value: boolean) => void
    setPushedBlock: (value: boolean) => void
    setPageTitle: (value: string) => void
  } => {
    const isPushed = ref(false)
    const isPushedBlock = ref(false)
    const pageTitle = ref('Welcome')

    function setPushed(value: boolean): void {
      isPushed.value = value
    }
    function setPushedBlock(value: boolean): void {
      isPushedBlock.value = value
    }
    function setPageTitle(value: string): void {
      pageTitle.value = value
    }

    return {
      isPushed,
      isPushedBlock,
      pageTitle,
      setPushed,
      setPushedBlock,
      setPageTitle,
    } as const
  }
)

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useViewWrapper, import.meta.hot))
}
