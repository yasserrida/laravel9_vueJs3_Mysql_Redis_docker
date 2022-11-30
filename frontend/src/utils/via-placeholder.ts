export function onceImageErrored(event: Event, size: string): void {
  const target = event.target as HTMLImageElement
  target.src = `https://via.placeholder.com/${size}`
}
