import type { Directive, DirectiveBinding, DirectiveHook } from 'vue'

const onUpdate: DirectiveHook = (el: HTMLElement, bindings: DirectiveBinding<any>): void => {
  const src: any = bindings.value.src
  const placeholder: any = bindings.value.placeholder

  if (src) {
    const image = new Image()

    if (placeholder) {
      image.onerror = (): void => {
        image.onerror = null
        el.style.backgroundImage = `url(${placeholder})`
      }
    }

    image.onload = (): void => {
      image.onload = null
      el.style.backgroundImage = `url(${src})`
    }

    image.src = src
  }
}

const background: Directive = {
  getSSRProps(): {} {
    return {}
  },
  updated: onUpdate,
  mounted: onUpdate,
}

export default background
