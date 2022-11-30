import { useUserSession } from '/@src/stores/userSession'

const userSession = useUserSession()

export function can(permission: string): boolean {
  return userSession.getPermissions.length && userSession.getPermissions.includes(permission) ? true : false
}

export function hasRole(role: string): boolean {
  return userSession.getRole.length && (userSession.getRole == role || userSession.getRole == 'ADMINISTRATEUR')
    ? true
    : false
}

export function hasRoleOnly(role: string): boolean {
  return userSession.getRole.length && userSession.getRole == role ? true : false
}

export function hasRoles(roles: Array<string>): boolean {
  roles.push('ADMINISTRATEUR')
  return userSession.getRole.length && roles.includes(userSession.getRole) ? true : false
}

export function hasRolesOnly(roles: Array<string>): boolean {
  return userSession.getRole.length && roles.includes(userSession.getRole) ? true : false
}
