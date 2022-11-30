import { ErrorObject } from '@vuelidate/core'

export const mapErrorMessage = (validator: string, error: any): string => {
  switch (validator) {
    case 'required':
      return 'Champ Obligatoire'
    case 'requiredIf':
      return 'Champ Obligatoire'
    case 'numeric':
      return 'Doit être numérique'
    case 'minValue':
      return `La valeur minimale est ${error.$params?.min}`
    case 'maxValue':
      return `La valeur maximale est ${error.$params?.max}`
    case 'minLength':
      return `Longeur minimale est ${error.$params?.min}`
    case 'maxLength':
      return `Longeur maximale est ${error.$params?.max}`
    case 'CheckDateSignature':
      return "Doit être inférieur ou égale à la date d'envoi"
    case 'CheckDateEnvoiContrat':
      return "Doit être supérieur ou égale à la date d'envoi du contrat"
    case 'CheckDateEnvoi':
      return 'Doit être supérieur ou égale à la date de reception'
    case 'ibanValid':
      return "n'est pas valide"
    case 'StartWithE':
      return 'Doit commancer avec la lettre E'
    case 'checkSomePER':
      return 'La somme des fond et UC doit être égale à 100'
    case 'isAgeAllowed':
      return "L'age doit être entre 18 et 99 ans"
    case 'dateMinToday':
      return "Doit être moin qu'aujourd'hui"
    default:
      return 'Erreur de validation'
  }
}

export const mapErrorMessages = (errors: ErrorObject[]): string => {
  let message = ''
  let required = false
  for (const error of errors) {
    if (error.$validator == 'required' || error.$validator == 'requiredIf') {
      if (!required) {
        message += `- Veuillez remplire les champs obligatoires <br/>`
        required = true
      }
    } else {
      message += `- ${error.$property.replaceAll('_', ' ')} : ${mapErrorMessage(error.$validator, error)} <br/>`
    }
  }
  return message
}

export const mapErrorMessagesToArray = (errors: ErrorObject[]): Array<string> => {
  const messages = []
  let required = false
  for (const error of errors) {
    if (error.$validator == 'required' || error.$validator == 'requiredIf') {
      if (!required) {
        messages.push(`Veuillez remplire les champs obligatoires`)
        required = true
      }
    } else {
      messages.push(`${error.$property.replaceAll('_', ' ')} : ${mapErrorMessage(error.$validator, error)}`)
    }
  }
  return messages
}
