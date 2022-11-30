import { createRouter as createClientRouter, createWebHistory, Router } from 'vue-router'

import routes from 'pages-generated'

export function createRouter(): Router {
  const router = createClientRouter({
    history: createWebHistory(),
    routes,
  })

  return router
}
