<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { reactive } from 'vue';

const { props } = usePage();
const prestamo = props.prestamo as {
  id: number;
  bienes: {
    id: number;
    nombre: string;
    pivot: {
      cantidad_prestada: number;
      cantidad_devuelta: number;
      estado_devolucion: string;
    };
  }[];
};

type BreadcrumbItem = { title: string; href: string };
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Préstamos', href: '/prestamo' },
  { title: 'Registrar Devolución', href: '#' },
];

const form = reactive({
  bienes: prestamo.bienes.map((bien) => ({
    bien_id: bien.id,
    cantidad_devuelta: bien.pivot.cantidad_devuelta || 0,
    estado_devolucion: bien.pivot.estado_devolucion || 'pendiente',
  })),
});

const submit = () => {
  router.put(`/prestamo/${prestamo.id}`, form);
};
</script>

<template>
  <Head title="Registrar Devolución" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <h1 class="text-2xl font-bold">Registrar Devolución</h1>

      <form @submit.prevent="submit" class="space-y-6 max-w-2xl">
        <div
          v-for="(item, index) in form.bienes"
          :key="item.bien_id"
          class="border p-4 rounded-lg bg-gray-50 space-y-4"
        >
          <h2 class="font-semibold text-lg">
            {{ prestamo.bienes[index].nombre }} (Prestado: {{ prestamo.bienes[index].pivot.cantidad_prestada }})
          </h2>

          <div>
            <Label :for="`cantidad-${index}`">Cantidad Devuelta</Label>
            <Input
              :id="`cantidad-${index}`"
              v-model="item.cantidad_devuelta"
              type="number"
              min="0"
              class="w-full"
            />
          </div>

          <div>
            <Label :for="`estado-${index}`">Estado de Devolución</Label>
            <select v-model="item.estado_devolucion" :id="`estado-${index}`" class="w-full border rounded p-2">
              <option value="completa">Completa</option>
              <option value="parcial">Parcial</option>
              <option value="dañada">Dañada</option>
            </select>
          </div>
        </div>

        <div class="flex gap-4">
          <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">Guardar</Button>
          <button onclick="window.location.href='/prestamo'" class="btn btn-outline">Cancelar</button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

