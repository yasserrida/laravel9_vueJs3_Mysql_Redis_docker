import { useUserSession } from '/@src/stores/userSession'
import Echo from 'laravel-echo'
import socketio from 'socket.io-client'

let socket: any = null

export function createSocekt(): void {
  if (!useUserSession().token) {
    socket = null
    return
  }
  socket = new Echo({
    broadcaster: 'socket.io',
    host: import.meta.env.VITE_WS_BASE_URL,
    client: socketio,
    transports: ['websocket'],
    authEndpoint: import.meta.env.VITE_API_BASE_URL + '/api/broadcasting/auth',
    auth: {
      headers: {
        Authorization: `Bearer ${useUserSession().token}`,
      },
    },
    forceTLS: false,
    disableStats: true,
  })

  return
}

export function disconnectSocket(): void {
  socket = null
}

export function useSocket(): any {
  if (!socket) createSocekt()
  return socket
}
