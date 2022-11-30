import { useApi } from '/@src/composable/useApi'
import { useNotyf } from '/@src/composable/useNotyf'
import { AxiosInstance } from 'axios'

const notif = useNotyf()
const api: AxiosInstance = useApi()

export async function getAll(): Promise<any> {
  try {
    const { data } = await api.get('/api/gamme')
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return []
  }
}

export async function get(id: string): Promise<any> {
  try {
    const { data } = await api.get(`/api/gamme/${id}`)
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return null
  }
}

export async function store(payload: any): Promise<boolean> {
  try {
    await api.post('/api/gamme', payload)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function update(id: string, payload: any): Promise<boolean> {
  try {
    await api.put(`/api/gamme/${id}`, payload)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function deleteItem(id: string): Promise<boolean> {
  try {
    await api.delete(`/api/gamme/${id}`)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}
