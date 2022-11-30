import { useNotyf } from '/@src/composable/useNotyf'
import { useApi } from '/@src/composable/useApi'
import { AxiosInstance } from 'axios'

const notif = useNotyf()
const api: AxiosInstance = useApi()

export async function getNotificationsList(payload: any): Promise<any> {
  try {
    const { data } = await api.get('/api/notification', payload)
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return { data: [], total: 0, per_page: 8 }
  }
}

export async function storeNotification(payload: any): Promise<boolean> {
  try {
    await api.post('/api/notification', payload)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function updateNotification(id: number, payload: any): Promise<boolean> {
  try {
    await api.put(`/api/notification/${id}`, payload)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function deleteNotification(id: number): Promise<boolean> {
  try {
    await api.delete(`/api/notification/${id}`)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}
