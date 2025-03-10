import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from '../src/router/index.ts'
import './style.css'
import App from './App.vue'

createApp(App)
  .use(createPinia())  // Usar Pinia aquí
  .use(router)         // Usar el router aquí
  .mount('#app')       // Monta la aplicación