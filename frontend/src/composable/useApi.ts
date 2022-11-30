import axios, { AxiosInstance, AxiosRequestConfig } from 'axios'

import { useUserSession } from '/@src/stores/userSession'

let api: AxiosInstance

export function createApi(): AxiosInstance {
  api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
  })

  api.interceptors.request.use((config: AxiosRequestConfig): AxiosRequestConfig<any> => {
    const userSession = useUserSession()

    if (userSession.isLoggedIn) {
      config.headers = {
        ...config.headers,
        Authorization: `Bearer ${userSession.token}`,
      }
      config.withCredentials = false
    }

    return config
  })

  return api
}

export function useApi(): AxiosInstance {
  if (!api) createApi()
  return api
}
