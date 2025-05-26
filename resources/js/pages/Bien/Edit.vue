<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { onMounted, reactive } from 'vue';
import { Bien } from '@/types';

const { props } = usePage();
const bien = props.bien as Bien;

type BreadcrumbItem = { title: string; href: string };
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Bien', href:'/bien' },
    { title: 'Editar Bien', href: '#' },
];

const form = reactive<Partial<{nombre: string; categoria: string; descripcion: string; codigo: number; cantidad: number}>>({
    nombre: '',
    categoria: '',
    descripcion: '',
    codigo: undefined,
    cantidad: undefined,
})

onMounted(() => { 
    form.nombre = bien.nombre ?? '';
    form.categoria = bien.categoria ?? '';
    form.descripcion = bien.descripcion ?? '';
    form.codigo = bien.codigo ?? undefined;
    form.cantidad = bien.cantidad ?? undefined;
});

const resetForm = () => {
    form.nombre= '', 
    form.categoria= '', 
    form.descripcion= '', 
    form.codigo= undefined, 
    form.cantidad= undefined;
};

const submit = () => {
    router.put(`/bien/${bien.id}`, form, {onSuccess: resetForm})
}

</script>

<template>
  <Head title="Editar Bienes" />
  <AppLayout :breadcrumbs="breadcrumbs"> 
    <h1 class="text-2x1 font-blod">Editar bien</h1>
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
                    <button as="a" href="/bien" variant="outline">Cancel</button>
                </div>
            </form>
        </div>
  </AppLayout>
</template>