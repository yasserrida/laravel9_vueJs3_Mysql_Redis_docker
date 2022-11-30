import { useNotyf } from '/@src/composable/useNotyf'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { AxiosInstance } from 'axios'

const userSession = useUserSession()
const notif = useNotyf()
const api: AxiosInstance = useApi()

export async function getReclamantionsList(payload: any): Promise<any> {
  try {
    const { data } = await api.get('/api/reclamation', payload)
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return { data: [], total: 0, per_page: 8 }
  }
}

export async function getReclamantionById(id: string): Promise<any> {
  try {
    const { data } = await api.get(`/api/reclamation/${id}`)
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return null
  }
}

export async function storeReclamantion(payload: any): Promise<boolean> {
  try {
    await api.post('/api/reclamation', payload, {
      headers: { 'Content-Type': 'multipart/form-data', Authorization: `Bearer ${userSession.token}` },
    })
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function updateReclamantion(id: string, payload: any): Promise<boolean> {
  try {
    await api.put(`/api/reclamation/${id}`, payload)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}
