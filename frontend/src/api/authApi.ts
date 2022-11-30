import { AxiosInstance } from 'axios'
import { useApi } from '/@src/composable/useApi'
import { useNotyf } from '/@src/composable/useNotyf'

const notif = useNotyf()
const api: AxiosInstance = useApi()

export async function login(payload: any): Promise<any> {
  try {
    const { data } = await api.post('/api/login', payload)
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return null
  }
}

export async function logout(): Promise<boolean> {
  try {
    await api.post('/api/logout')
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function me(): Promise<any> {
  try {
    const { data } = await api.get('/api/users/me')
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return null
  }
}
