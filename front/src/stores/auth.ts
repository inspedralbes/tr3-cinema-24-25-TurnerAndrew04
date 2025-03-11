import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import Cookies from 'js-cookie'

const API_URL = 'http://localhost:8000/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<any>(null)
  const token = ref<string | null>(Cookies.get('token') || null)

  const isAuthenticated = computed(() => !!token.value)

  async function login(email: string, password: string) {
    try {
      const response = await axios.post(`${API_URL}/login`, {
        email,
        password
      })
      
      token.value = response.data.token
      user.value = response.data.user
      Cookies.set('token', response.data.token)
      
      // Configure axios to use the token
      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      
      return response.data
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error during login')
    }
  }

  async function register(name: string, email: string, password: string) {
    try {
      const response = await axios.post(`${API_URL}/register`, {
        name,
        email,
        password,
        password_confirmation: password
      })
      
      token.value = response.data.token
      user.value = response.data.user
      Cookies.set('token', response.data.token)
      
      // Configure axios to use the token
      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      
      return response.data
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error during registration')
    }
  }

  function logout() {
    token.value = null
    user.value = null
    Cookies.remove('token')
    delete axios.defaults.headers.common['Authorization']
  }

  // Initialize axios with token if it exists
  if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout
  }
})