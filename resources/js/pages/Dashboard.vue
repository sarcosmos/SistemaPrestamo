<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import {
  PackageOpen,
  ChartNoAxesColumn,
  FileSpreadsheet,
  Archive,
  PackageCheck,
  FileCheck,
  User
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Panel',
    href: '/dashboard',
  },
];

const { props } = usePage<{
  totalBienes: number;
  sumaCantidad: number;
  totalUsuarios: number;
  totalPrestamos: number;
  totalDevoluciones: number;
  totalFacturas: number;
  totalFacturasPrestamo: number;
  totalFacturasDevolucion: number;
}>();

const totalBienes = props.totalBienes;
const sumaCantidad = props.sumaCantidad;
const totalUsuarios = props.totalUsuarios;
const totalPrestamos = props.totalPrestamos;
const totalDevoluciones = props.totalDevoluciones;
const totalFacturas = props.totalFacturas;
const totalFacturasPrestamo = props.totalFacturasPrestamo;
const totalFacturasDevolucion = props.totalFacturasDevolucion;

</script>

<template>
  <Head title="Panel" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="grid auto-rows-min gap-5 md:grid-cols-4">
        <!-- Préstamos -->
        <div class="relative aspect-video overflow-hidden rounded-xl flex flex-col justify-between p-4 bg-red-500 text-white shadow-lg transition-transform duration-300 hover:scale-105">
          <PackageOpen class="w-10 h-10" stroke-width="1" />
          <span class="text-lg font-semibold">Préstamos</span>
          <p class="text-3xl font-bold">{{ totalPrestamos }}</p>
        </div>

        <!-- Inventario Actual -->
        <div class="relative aspect-video overflow-hidden rounded-xl flex flex-col justify-between p-4 bg-blue-500 text-white shadow-lg transition-transform duration-300 hover:scale-105">
          <ChartNoAxesColumn class="w-10 h-10" stroke-width="1" />
          <div>
            <span class="text-lg font-semibold">Inventario Actual</span>
            <p class="text-3xl font-bold">{{ sumaCantidad }}</p>
          </div>
        </div>

        <!-- Facturas de Préstamos -->
        <div class="relative aspect-video overflow-hidden rounded-xl flex flex-col justify-between p-4 bg-green-500 text-white shadow-lg transition-transform duration-300 hover:scale-105">
          <FileSpreadsheet class="w-10 h-10" stroke-width="1" />
          <span class="text-lg font-semibold">Facturas de Préstamos</span>
          <p class="text-3xl font-bold">{{ totalFacturasPrestamo }}</p>
        </div>

        <!-- Bienes -->
        <div class="relative aspect-video overflow-hidden rounded-xl flex flex-col justify-between p-4 bg-yellow-500 text-white shadow-lg transition-transform duration-300 hover:scale-105">
          <Archive class="w-10 h-10" stroke-width="1" />
          <div>
            <span class="text-lg font-semibold">Bienes</span>
            <p class="text-3xl font-bold">{{ totalBienes }}</p>
          </div>
        </div>

        <!-- Devoluciones -->
        <div class="relative aspect-video overflow-hidden rounded-xl flex flex-col justify-between p-4 bg-purple-500 text-white shadow-lg transition-transform duration-300 hover:scale-105">
          <PackageCheck class="w-10 h-10" stroke-width="1" />
          <span class="text-lg font-semibold">Devoluciones</span>
          <p class="text-3xl font-bold">{{ totalDevoluciones }}</p>
        </div>

        <!-- Facturas de Devoluciones -->
        <div class="relative aspect-video overflow-hidden rounded-xl flex flex-col justify-between p-4 bg-pink-500 text-white shadow-lg transition-transform duration-300 hover:scale-105">
          <FileCheck class="w-10 h-10" stroke-width="1" />
          <span class="text-lg font-semibold">Facturas de Devoluciones</span>
          <p class="text-3xl font-bold">{{ totalFacturasDevolucion }}</p>
        </div>

        <!-- Usuarios -->
        <div class="relative aspect-video overflow-hidden rounded-xl flex flex-col justify-between p-4 bg-teal-500 text-white shadow-lg transition-transform duration-300 hover:scale-105">
          <User class="w-10 h-10" stroke-width="1" />
          <span class="text-lg font-semibold">Usuarios</span>
          <p class="text-3xl font-bold">{{ totalUsuarios }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

