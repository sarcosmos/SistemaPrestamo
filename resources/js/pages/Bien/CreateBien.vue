<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import { ref } from 'vue';
import Input from '@/components/ui/input/Input.vue';

type BreadcrumbItem = { title: string; href: string };
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Bien', href:'/bien' },
    { title: 'Guardar Bienes', href: '#' },
];

const form = ref<Partial<{nombre: string; categoria: string; descripcion: string; codigo: number; cantidad: number}>>({
    nombre: '',
    categoria: '',
    descripcion: '',
    codigo: undefined,
    cantidad: undefined,
})

const resetForm = () => {
    form.value = { nombre: '', categoria: '', descripcion: '', codigo: undefined, cantidad: undefined, }
}

const submit = () => {
    router.post('/bien', form.value, {onSuccess: resetForm})
}


</script>

<template>
  <Head title="Guardar Bienes" />
  <AppLayout :breadcrumbs="breadcrumbs"> 
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2x1 font-blod">Crear Bien</h1>
            <form @submit.prevent="submit" class="space-y-6 max-w-lg">
                <div v-for="(label, key) in {nombre: 'Nombre', categoria: 'Categoria', descripcion: 'Descripción', codigo: 'Codigo', cantidad: 'Cantidad'}" :key="key">
                    <Label :for="key">{{ label }}</Label>
                    <Input
                    :id="key" 
                    v-model="form[key]"
                    :type="key === 'codigo' ? 'number' : key === 'cantidad' ? 'number' : 'text'"
                    :placeholder="label"
                    />
                </div>
                <div class="flex gap-4">
                    <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">Guardar</Button>
                    <button onclick="window.location.href='/bien'" class="btn btn-outline">Cancel</button>
                </div>
            </form>
        </div>
  </AppLayout>
</template>