import { AxiosInstance } from 'axios'
import { useApi } from '/@src/composable/useApi'

const api: AxiosInstance = useApi()

export async function getVillesByCP(cp: string): Promise<Array<any>> {
  try {
    const { data } = await api.get(`/api/common/villes/${cp}/normal`)
    return data
  } catch (error: any) {
    return []
  }
}

export async function getProduits(): Promise<Array<any>> {
  try {
    const { data } = await api.get(`/api/common/produits`)
    return data
  } catch (error: any) {
    return []
  }
}

export async function getFournisseurs(): Promise<Array<any>> {
  try {
    const { data } = await api.get(`/api/common/fournisseurs`)
    return data
  } catch (error: any) {
    return []
  }
}

export async function getGammes(): Promise<Array<any>> {
  try {
    const { data } = await api.get(`/api/common/gammes`)
    return data
  } catch (error: any) {
    return []
  }
}
