import { useApi } from '/@src/composable/useApi'
import { AxiosInstance } from 'axios'

const api: AxiosInstance = useApi()

export async function getNotifications(): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/notif/all')
    return data
  } catch (error: any) {
    return []
  }
}

export async function getNotificationsUnreaded(): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/notif/unreaded')
    return data
  } catch (error: any) {
    return []
  }
}

export async function markNotifications(): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/notif/mark')
    return data
  } catch (error: any) {
    return []
  }
}
