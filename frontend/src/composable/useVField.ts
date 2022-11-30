import { reactive, ref, InjectionKey } from 'vue'

let fieldId: number = 0

export type UseVField = ReturnType<typeof useVField>

export const useVFieldSymbol: InjectionKey<UseVField> = Symbol()

export function useVField(): { id: string } {
  const id = ref(`field-${fieldId++}`)

  return reactive({
    id,
  } as const)
}
