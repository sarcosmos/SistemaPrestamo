<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-200">
    <div class="bg-white rounded-2xl shadow-2xl p-10 max-w-xl w-full text-center space-y-6">
      
      <!-- Título sin animación -->
      <h1 class="text-5xl font-bold text-blue-700">Chía</h1>

      <!-- Descripción -->
      <p class="text-gray-600 text-lg">
        Bienvenido al sistema de préstamo de bienes de la Alcaldía de Chía. 
        Este sistema permite gestionar de forma eficiente los recursos disponibles 
        para su préstamo, seguimiento y administración.
      </p>

      <!-- Botones -->
      <div class="flex justify-center space-x-4">
        <Link
          href="/login"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition"
        >
          Iniciar sesión
        </Link>

        <Link
          href="/register"
          class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold py-2 px-6 rounded-lg border border-blue-300 transition"
        >
          Registrarse
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>



