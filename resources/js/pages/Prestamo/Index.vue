<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { CirclePlus, Eye } from 'lucide-vue-next';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableRow, TableHeader } from '@/components/ui/table';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';

interface PrestamoPageProps extends SharedData {
  prestamos: {
    id: number;
    fecha_prestamo: string;
    fecha_devolucion: string | null;
    usuario: { name: string };
    bienes: {
      id: number;
      nombre: string;
      pivot: {
        cantidad_prestada: number;
        cantidad_devuelta: number;
        estado_devolucion: string;
      };
    }[];
  }[];
}

const { props } = usePage<PrestamoPageProps>();
const prestamos = computed(() => props.prestamos);

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Préstamos',
    href: '/prestamo',
  },
];
</script>

<template>
  <Head title="Préstamos" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="flex">
        <Button asChild size="sm" class="bg-indigo-500 text-white hover:bg-indigo-700 px-3 py-2">
          <Link href="/prestamo/create" class="flex items-center gap-2">
            <CirclePlus class="w-4 h-4" />
            <span>Registrar Préstamo</span>
          </Link>
        </Button>
      </div>
    </div>

    <div class="relative min-h-[100vh] flex-1 rounded-x1 border-sidebar-border/70">
      <Table>
        <TableCaption>Lista de Préstamos</TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead class="text-center">Usuario</TableHead>
            <TableHead class="text-center">Fecha Préstamo</TableHead>
            <TableHead class="text-center">Fecha Devolución</TableHead>
            <TableHead class="text-center">Bien</TableHead>
            <TableHead class="text-center">Cantidad Prestada</TableHead>
            <TableHead class="text-center">Cantidad Devuelta</TableHead>
            <TableHead class="text-center">Estado Devolución</TableHead>
            <TableHead class="text-center">Acciones</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <template v-for="prestamo in prestamos" :key="prestamo.id">
            <template v-for="(bien, index) in prestamo.bienes" :key="bien.id">
              <TableRow>
                <TableCell class="text-center" v-if="index === 0" :rowspan="prestamo.bienes.length">
                  {{ prestamo.usuario.name }}
                </TableCell>
                <TableCell class="text-center" v-if="index === 0" :rowspan="prestamo.bienes.length">
                  {{ new Date(prestamo.fecha_prestamo).toLocaleDateString() }}
                </TableCell>
                <TableCell class="text-center" v-if="index === 0" :rowspan="prestamo.bienes.length">
                  {{ prestamo.fecha_devolucion ? new Date(prestamo.fecha_devolucion).toLocaleDateString() : 'Pendiente' }}
                </TableCell>
                <TableCell class="text-left">{{ bien.nombre }}</TableCell>
                <TableCell class="text-center">{{ bien.pivot.cantidad_prestada }}</TableCell>
                <TableCell class="text-center">{{ bien.pivot.cantidad_devuelta }}</TableCell>
                <TableCell class="text-center">
                  <span
                    :class="{
                      'bg-green-100 text-green-800 px-2 py-1 rounded': bien.pivot.estado_devolucion === 'completa',
                      'bg-yellow-100 text-yellow-800 px-2 py-1 rounded': bien.pivot.estado_devolucion === 'parcial',
                      'bg-red-100 text-red-800 px-2 py-1 rounded': bien.pivot.estado_devolucion === 'dañada',
                      'bg-gray-100 text-gray-800 px-2 py-1 rounded': !bien.pivot.estado_devolucion || bien.pivot.estado_devolucion === 'pendiente',
                    }"
                  >
                    {{
                      bien.pivot.estado_devolucion
                        ? bien.pivot.estado_devolucion.charAt(0).toUpperCase() + bien.pivot.estado_devolucion.slice(1)
                        : 'Pendiente'
                    }}
                  </span>
                </TableCell>
                <TableCell class="text-center" v-if="index === 0" :rowspan="prestamo.bienes.length">
                  <Button asChild size="sm" class="bg-blue-500 text-white hover:bg-blue-700">
                    <Link :href="`/prestamo/${prestamo.id}/edit`">
                      <Eye class="w-4 h-4" />
                    </Link>
                  </Button>
                </TableCell>
              </TableRow>
            </template>
          </template>
        </TableBody>
      </Table>
    </div>
  </AppLayout>
</template>


