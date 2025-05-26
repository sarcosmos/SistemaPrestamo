<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { ref } from 'vue';

defineProps<{
  usuarios: { id: number; name: string }[];
  bienes: { id: number; nombre: string; cantidad: number }[];
}>();

type BreadcrumbItem = { title: string; href: string };
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Préstamos', href: '/prestamo' },
  { title: 'Registrar Préstamo', href: '#' },
];

const selectedUser = ref<number | null>(null);
const selectedBienes = ref<{ bien_id: number; cantidad: number }[]>([]);

const addBien = () => {
  selectedBienes.value.push({ bien_id: 0, cantidad: 1 });
};

const removeBien = (index: number) => {
  selectedBienes.value.splice(index, 1);
};

const resetForm = () => {
  selectedUser.value = null;
  selectedBienes.value = [];
};

const submit = () => {
  router.post('/prestamo', {
    user_id: selectedUser.value,
    bienes: selectedBienes.value,
  }, {
    onSuccess: resetForm
  });
};
</script>

<template>
  <Head title="Registrar Préstamo" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <h1 class="text-2xl font-bold">Registrar Préstamo</h1>

      <form @submit.prevent="submit" class="space-y-6 max-w-2xl">
        <!-- Usuario -->
        <div>
          <Label for="usuario">Usuario</Label>
          <select v-model="selectedUser" id="usuario" class="w-full border rounded p-2">
            <option disabled value="">Seleccione un usuario</option>
            <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">
              {{ usuario.name }}
            </option>
          </select>
        </div>

        <!-- Bienes -->
        <div>
          <Label>Bienes a prestar</Label>
          <div v-for="(item, index) in selectedBienes" :key="index" class="flex gap-4 items-end mb-2">
            <div class="flex-1">
              <Label :for="`bien-${index}`">Bien</Label>
              <select v-model="item.bien_id" :id="`bien-${index}`" class="w-full border rounded p-2">
                <option disabled value="0">Seleccione un bien</option>
                <option v-for="bien in bienes" :key="bien.id" :value="bien.id">
                  {{ bien.nombre }} (Disponible: {{ bien.cantidad }})
                </option>
              </select>
            </div>
            <div class="w-32">
              <Label :for="`cantidad-${index}`">Cantidad</Label>
              <Input
                :id="`cantidad-${index}`"
                v-model="item.cantidad"
                type="number"
                min="1"
                placeholder="Cantidad"
              />
            </div>
            <Button type="button" class="bg-rose-500 hover:bg-rose-600" @click="removeBien(index)">
              Eliminar
            </Button>
          </div>
          <Button type="button" class="mt-2 bg-blue-500 hover:bg-blue-600" @click="addBien">
            + Agregar bien
          </Button>
        </div>

        <!-- Botones -->
        <div class="flex gap-4">
          <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">Guardar</Button>
          <button onclick="window.location.href='/prestamo'" class="btn btn-outline">Cancelar</button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

