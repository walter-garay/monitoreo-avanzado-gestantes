<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/lib/dateUtils';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Gestante {
    id: number;
    name: string;
    apellidos?: string;
    fecha_nacimiento?: string;
    fecha_inicio_gestacion?: string;
    peso_kg?: number;
    altura_cm?: number;
    centro_id?: number;
    email: string;
}

const props = defineProps({
    gestantes: {
        type: Array as () => Gestante[],
        required: true,
    },
    filters: {
        type: Object as () => { search?: string },
        default: () => ({}),
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gestantes',
        href: '/gestantes',
    },
];

const search = ref(props.filters.search || '');

// Manual debounce implementation
let timeoutId: ReturnType<typeof setTimeout> | null = null;

const handleSearch = () => {
    if (timeoutId) {
        clearTimeout(timeoutId);
    }
    timeoutId = setTimeout(() => {
        router.get(
            '/gestantes',
            {
                search: search.value,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }, 500);
};

watch(search, handleSearch);
</script>

<template>
    <Head title="Gestantes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h2 class="text-2xl font-bold tracking-tight">Gestantes</h2>

            <!-- Search Input -->
            <div class="mb-4">
                <Input v-model="search" placeholder="Buscar gestante..." />
            </div>

            <div v-if="gestantes.length > 0" class="grid gap-4">
                <Card v-for="gestante in gestantes" :key="gestante.id">
                    <CardHeader>
                        <CardTitle>{{ gestante.name }} {{ gestante.apellidos }}</CardTitle>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-2">
                        <CardDescription><strong>Correo:</strong> {{ gestante.email }}</CardDescription>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <CardDescription v-if="gestante.fecha_nacimiento">
                                <strong>Nacimiento:</strong> {{ formatDate(gestante.fecha_nacimiento, 'medium') }}
                            </CardDescription>
                            <CardDescription v-if="gestante.fecha_inicio_gestacion">
                                <strong>Inicio gestación:</strong> {{ formatDate(gestante.fecha_inicio_gestacion, 'medium') }}
                            </CardDescription>
                            <CardDescription v-if="gestante.peso_kg"> <strong>Peso:</strong> {{ gestante.peso_kg }} kg </CardDescription>
                            <CardDescription v-if="gestante.altura_cm"> <strong>Altura:</strong> {{ gestante.altura_cm }} cm </CardDescription>
                        </div>
                        <CardDescription v-if="gestante.centro_id"> <strong>ID Centro de Salud:</strong> {{ gestante.centro_id }} </CardDescription>
                    </CardContent>
                    <CardFooter class="flex justify-end">
                        <Button @click="router.get(`/monitor/${gestante.id}`)">Monitorear</Button>
                    </CardFooter>
                </Card>
            </div>
            <div v-else class="text-muted-foreground text-center">No hay gestantes registradas en este momento.</div>
        </div>
    </AppLayout>
</template>
