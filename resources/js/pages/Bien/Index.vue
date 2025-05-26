<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Bien, type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import {  CirclePlus, Pencil, Trash  } from 'lucide-vue-next';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableRow, TableHeader } from '@/components/ui/table';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';

interface BienPageProps extends SharedData{
  bien: Bien[];
}
const{props} = usePage<BienPageProps>();
const bien = computed(()=>props.bien)

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Bien',
        href: '/bien',
    },
];


const deleteBien = async (id : number) => {
  if (!window.confirm('¿Está seguro de eliminar este bien?')) return;
    router.delete(`/bien/${id}`, { // ← usa backticks aquí
    preserveScroll: true,
    onSuccess: () => {
    router.visit('/bien', { replace: true });
  },
    onError: (errors) => {
    console.error('Error deleting bien', errors);
  }
  });
};


</script>


<template>
  <Head title="Bien" />

  <AppLayout :breadcrumbs="breadcrumbs"> 

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="flex">
        <Button asChild size="sm" class="bg-indigo-500 text-white hover:bg-indigo-700 px-3 py-2">
          <Link href="/bien/create" class="flex items-center gap-2">
            <CirclePlus class="w-4 h-4" />
              <span>Create</span>
          </Link>
        </Button>
      </div>
    </div>

    <div class="relative min-h-[100vh] flex-1 rounded-x1 border-sidebar-border/70">
        <Table>
          <TableCaption>Lista de Bienes</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead class="text-center">Nombre</TableHead>
              <TableHead class="text-center">Categoria</TableHead>
              <TableHead class="text-center">Descripción</TableHead>
              <TableHead class="text-center">Codigo</TableHead>
              <TableHead class="text-center">Cantidad</TableHead>
              <TableHead class="text-center">Estado</TableHead>
              <TableHead class="text-center">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="bien in bien" :key="bien.id">
              <TableCell class="text-center">{{ bien.nombre }}</TableCell>
              <TableCell class="text-center">{{ bien.categoria }}</TableCell>
              <TableCell class="text-center">{{ bien.descripcion }}</TableCell>
              <TableCell class="text-center">{{ bien.codigo }}</TableCell>
              <TableCell class="text-center">{{ bien.cantidad }}</TableCell>
              <TableCell class="text-center">{{ bien.estado }}</TableCell>
              <TableCell>
              <div class="flex gap-2 justify-center">
                <Button asChild size="sm" class="bg-blue-500 text-white hover:bg-blue-700">
                  <Link :href="`/bien/${bien.id}/edit`">
                    <Pencil />
                  </Link>
                </Button>

                <Button asChild size="sm" class="bg-rose-500 text-white hover:bg-rose-700" @click="deleteBien(bien.id)">
                  <Trash class="w-10 h-8" />
                </Button>
              </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
    </div>
  </AppLayout>
</template>
