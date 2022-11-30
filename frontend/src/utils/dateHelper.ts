export function getCurrentDate(): string {
  return new Date().toJSON().slice(0, 10)
}

export function getAge(dateN: string): number {
  return new Date().getFullYear() - new Date(dateN).getFullYear()
}
