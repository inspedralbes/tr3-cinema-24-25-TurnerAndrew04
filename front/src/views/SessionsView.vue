<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { Session, Movie } from '../types'
import SessionCard from '../components/SessionCard.vue'

const sessions = ref<Array<{ session: Session, movie: Movie }>>([])

onMounted(async () => {
  // Sample data for 3 different sessions
  sessions.value = [
    {
      session: {
        id: 1,
        movieId: "tt0468569",
        date: "2025-03-20",
        time: "16:00",
        isSpecialDay: false
      },
      movie: {
        id: "tt0468569",
        title: "The Dark Knight",
        year: "2008",
        poster: "https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg",
        plot: "When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.",
        duration: "152 min"
      }
    },
    {
      session: {
        id: 2,
        movieId: "tt0109830",
        date: "2025-03-20",
        time: "18:00",
        isSpecialDay: true
      },
      movie: {
        id: "tt0109830",
        title: "Forrest Gump",
        year: "1994",
        poster: "https://m.media-amazon.com/images/M/MV5BNWIwODRlZTUtY2U3ZS00Yzg1LWJhNzYtMmZiYmEyNmU1NjMzXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SX300.jpg",
        plot: "The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an IQ of 75.",
        duration: "142 min"
      }
    },
    {
      session: {
        id: 3,
        movieId: "tt0111161",
        date: "2025-03-20",
        time: "20:00",
        isSpecialDay: false
      },
      movie: {
        id: "tt0111161",
        title: "The Shawshank Redemption",
        year: "1994",
        poster: "https://m.media-amazon.com/images/M/MV5BNDE3ODcxYzMtY2YzZC00NmNlLWJiNDMtZDViZWM2MzIxZDYwXkEyXkFqcGdeQXVyNjAwNDUxODI@._V1_SX300.jpg",
        plot: "Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.",
        duration: "142 min"
      }
    }
  ]
})
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Sessions disponibles</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <SessionCard
        v-for="{ session, movie } in sessions"
        :key="session.id"
        :session="session"
        :movie="movie"
      />
    </div>
  </div>
</template>