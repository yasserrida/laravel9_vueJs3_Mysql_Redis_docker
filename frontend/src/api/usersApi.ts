import { useNotyf } from '/@src/composable/useNotyf'
import { useApi } from '/@src/composable/useApi'
import { AxiosInstance } from 'axios'

const notif = useNotyf()
const api: AxiosInstance = useApi()

export async function getUserssList(): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/users')
    return data
  } catch (error: any) {
    notif.error(error.response.data.message)
    return []
  }
}

export async function storeUser(payload: any): Promise<boolean> {
  try {
    await api.post('/api/users', payload)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function UpdateUser(id: number, payload: any): Promise<boolean> {
  try {
    await api.put(`/api/users/${id}`, payload)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function UpdateSelf(payload: any): Promise<boolean> {
  try {
    await api.post(`/api/users/me`, payload)
    return true
  } catch (error: any) {
    notif.error(error.response.data.message)
    return false
  }
}

export async function getRolesList(): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/common/roles')
    return data
  } catch (error: any) {
    return []
  }
}

export async function getPermissionsList(): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/common/permissions')
    return data
  } catch (error: any) {
    return []
  }
}

export async function getUserPermissionsList(id: string): Promise<Array<any>> {
  try {
    const { data } = await api.get(`/api/users/permissions/${id}`)
    return data
  } catch (error: any) {
    return []
  }
}

export async function getPermissionsListByRole(role: string): Promise<Array<any>> {
  try {
    const { data } = await api.get(`/api/common/permissions/${role}`)
    return data
  } catch (error: any) {
    return []
  }
}

export async function getResponsablesList(): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/users/responsables')
    return data
  } catch (error: any) {
    return []
  }
}

export async function getGestionnairesList(): Promise<Array<any>> {
  try {
    const { data } = await api.get('/api/users/gestionnaires')
    return data
  } catch (error: any) {
    return []
  }
}
