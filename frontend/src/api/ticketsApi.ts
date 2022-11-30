import { useNotyf } from '/@src/composable/useNotyf'
import { useApi } from '/@src/composable/useApi'
import { AxiosInstance } from 'axios'
import { useUserSession } from '/@src/stores/userSession'

const userSession = useUserSession()
const notif = useNotyf()
const api: AxiosInstance = useApi()

export async function getTicketsList(payload: any): Promise<any> {
  try {
    const { data } = await api.get('/api/ticket', payload)
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return { data: [], total: 0, per_page: 8 }
  }
}

export async function storeTicket(payload: any): Promise<boolean> {
  try {
    await api.post('/api/ticket', payload, {
      headers: { 'Content-Type': 'multipart/form-data', Authorization: `Bearer ${userSession.token}` },
    })
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function updateTicket(id: number, payload: any): Promise<boolean> {
  try {
    await api.post(`/api/ticket/${id}`, payload, {
      headers: { 'Content-Type': 'multipart/form-data', Authorization: `Bearer ${userSession.token}` },
    })
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function getDocuments(id: string): Promise<Array<any>> {
  try {
    const { data } = await api.get(`/api/ticket/documents/${id}`)
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return []
  }
}
