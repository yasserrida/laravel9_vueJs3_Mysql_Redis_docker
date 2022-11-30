<route lang="yaml">
redirect:
  name: auth-login
meta:
  notAuth: true
</route>

<template>
  <AuthLayout>
    <RouterView />
  </AuthLayout>
</template>

<style lang="scss">
@import './../scss/auth.scss';
</style>
