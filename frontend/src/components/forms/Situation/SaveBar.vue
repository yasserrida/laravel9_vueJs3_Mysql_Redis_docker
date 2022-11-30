<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { computed } from 'vue'
import { can } from '/@src/utils/PermissionsHelper'

const { y } = useWindowScroll()
const isStuck = computed((): boolean => {
  return y.value > 30
})

const props = defineProps({
  title: { type: String, default: 'Situation' },
  buttonText: { type: String, default: 'Enregitrer' },
  icon: { type: String, default: 'feather:save' },
  isLoading: { type: Boolean, default: false },
  // isOperation: { type: Boolean, default: false },
  isContratEdit: { type: Boolean, default: false, required: false },
  isContrat: { type: Boolean, default: false, required: false },
})

defineEmits(['validate', 'clearOperation', 'setAsNew', 'allowUpdate'])
</script>
<template>
  <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
    <div class="form-header-inner">
      <div class="left">
        <h3>{{ title }}</h3>
      </div>
      <div class="right">
        <div class="buttons">
          <VButton
            v-if="!props.isContratEdit"
            raised
            style="background: var(--primary--dark-color) !important; color: white"
            :icon="props.icon"
            :loading="props.isLoading"
            @click="$emit('validate')"
            >{{ props.buttonText }}</VButton
          >
          <!-- <VButton
            v-if="props.isOperation"
            raised
            color="light"
            icon="feather:repeat"
            style="color: red !important"
            @click="$emit('clearOperation')"
            ><span style="color: #919191 !important">Changer Contrat</span></VButton
          > -->
          <VButton
            v-if="props.isContratEdit && can('CONTRAT-UPDATE')"
            raised
            icon="feather:edit"
            style="background: var(--primary--dark-color) !important; color: white"
            @click="$emit('allowUpdate')"
            >Modifier</VButton
          >
          <VButton
            v-if="props.isContrat && can('CONTRAT-CREATE')"
            raised
            color="light"
            icon="feather:plus"
            style="color: red !important"
            @click="$emit('setAsNew')"
            ><span style="color: #919191 !important">Créer Contrat</span></VButton
          >
        </div>
      </div>
    </div>
  </div>
</template>
