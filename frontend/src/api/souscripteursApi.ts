import { useNotyf } from '/@src/composable/useNotyf'
import { useApi } from '/@src/composable/useApi'
import { AxiosInstance } from 'axios'

const notif = useNotyf()
const api: AxiosInstance = useApi()

export async function getPersonnesByName(name: string): Promise<Array<any>> {
  try {
    const { data } = await api.get(`/api/personne`, { params: { name: name } })
    return data
  } catch (error: any) {
    return []
  }
}

export async function getPersonnes(payload: any): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/personne', { params: { ...payload } })
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return []
  }
}

