import { defineConfig } from 'vite'
import path from 'path'
import Vue from '@vitejs/plugin-vue'
import Pages from 'vite-plugin-pages'
import Components from 'unplugin-vue-components/vite'
import ViteFonts from 'vite-plugin-fonts'
import ViteRadar from 'vite-plugin-radar'
import PurgeIcons from 'vite-plugin-purge-icons'
import { imagetools } from 'vite-imagetools'
import ImageMin from 'vite-plugin-imagemin'
import { vueI18n } from '@intlify/vite-plugin-vue-i18n'
import { VitePWA } from 'vite-plugin-pwa'
import purgecss from 'rollup-plugin-purgecss'

const SILENT = Boolean(process.env.SILENT) ?? false
const SOURCE_MAP = Boolean(process.env.SOURCE_MAP) ?? false

export default defineConfig({
  root: process.cwd(),
  base: '/',
  publicDir: 'public',
  logLevel: SILENT ? 'error' : 'info',

  optimizeDeps: {
    include: [
      '@ckeditor/ckeditor5-vue',
      '@ckeditor/ckeditor5-build-classic',
      '@fortawesome/fontawesome-free',
      '@iconify/iconify',
      '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.min.js',
      '@vueuse/core',
      '@vueuse/head',
      '@vueform/multiselect',
      '@vuelidate/core',
      '@vuelidate/validators',
      'axios',
      'ag-grid-community',
      'ag-grid-vue3',
      'billboard.js',
      'dayjs',
      'dropzone',
      'filepond',
      'filepond-plugin-file-validate-size',
      'filepond-plugin-file-validate-type',
      'filepond-plugin-image-exif-orientation',
      'filepond-plugin-image-crop',
      'filepond-plugin-image-edit',
      'filepond-plugin-image-preview',
      'filepond-plugin-image-resize',
      'filepond-plugin-image-transform',
      'imask',
      'laravel-echo',
      'nprogress',
      'notyf',
      'mapbox-gl',
      'socket.io',
      'socket.io-client',
      'v-calendar',
      'vee-validate',
      'vue',
      'vue-scrollto',
      'vue3-apexcharts',
      'vue-tippy',
      'socket.io-client',
      'vue-accessible-color-picker',
    ],
  },
  resolve: {
    alias: [
      {
        find: '/@src/',
        replacement: `/src/`,
      },
    ],
  },
  build: {
    minify: false,
    sourcemap: SOURCE_MAP,
    brotliSize: !SILENT,
    chunkSizeWarningLimit: 2000,
  },
  plugins: [
    Vue({
      include: [/\.vue$/],
    }),

    vueI18n({
      include: path.resolve(__dirname, './src/locales/**'),
    }),

    Pages({
      nuxtStyle: false,
      pagesDir: [
        {
          dir: 'src/pages',
          baseRoute: '',
        },
      ],
    }),

    Components({
      dirs: ['src/components', 'src/layouts'],
      extensions: ['vue', 'md'],
      dts: true,
      include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
    }),

    PurgeIcons(),

    ViteFonts({
      google: {
        families: [
          {
            name: 'Fira Code',
            styles: 'wght@400;600',
          },
          {
            name: 'Montserrat',
            styles: 'wght@500;600;700;800;900',
          },
          {
            name: 'Roboto',
            styles: 'wght@300;400;500;600;700',
          },
        ],
      },
    }),

    ViteRadar({
      enableDev: true,
      analytics: {
        id: 'G-8PH6FM2JEL',
      },
    }),

    VitePWA({
      base: '/',
      includeAssets: ['favicon.svg', 'favicon.ico', 'robots.txt', 'apple-touch-icon.png'],
      manifest: {
        name: 'yasserApp',
        short_name: 'yasserApp',
        start_url: '/?utm_source=pwa',
        display: 'standalone',
        theme_color: '#ffffff',
        background_color: '#ffffff',
        icons: [
          {
            src: 'pwa-192x192.png',
            sizes: '192x192',
            type: 'image/png',
          },
          {
            src: 'pwa-512x512.png',
            sizes: '512x512',
            type: 'image/png',
          },
          {
            src: 'pwa-512x512.png',
            sizes: '512x512',
            type: 'image/png',
            purpose: 'any maskable',
          },
        ],
      },
      workbox: {
        maximumFileSizeToCacheInBytes: 3000000,
      },
    }),

    purgecss({
      content: [`./src/**/*.vue`],
      variables: false,
      safelist: {
        standard: [
          /(autv|lnil|lnir|fas?)/,
          /-(leave|enter|appear)(|-(to|from|active))$/,
          /^(?!(|.*?:)cursor-move).+-move$/,
          /^router-link(|-exact)-active$/,
          /data-v-.*/,
        ],
      },
      defaultExtractor(content) {
        const contentWithoutStyleBlocks = content.replace(/<style[^]+?<\/style>/gi, '')
        return contentWithoutStyleBlocks.match(/[A-Za-z0-9-_/:]*[A-Za-z0-9-_/]+/g) || []
      },
    }),

    imagetools({
      silent: SILENT,
    }),

    ImageMin({
      verbose: !SILENT,
      gifsicle: {
        optimizationLevel: 7,
        interlaced: false,
      },
      optipng: {
        optimizationLevel: 7,
      },
      mozjpeg: {
        quality: 60,
      },
      pngquant: {
        quality: [0.8, 0.9],
        speed: 4,
      },
      svgo: {
        plugins: [
          {
            name: 'removeViewBox',
            active: false,
          },
          {
            name: 'removeEmptyAttrs',
            active: false,
          },
        ],
      },
    }),
  ],
})
