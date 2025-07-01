<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type PropType } from 'vue';
import ListaSintomas from './componentes/ListaSintomas.vue';
import SeguimientoSalud from './componentes/SeguimientoSalud.vue';

defineProps({
    gestante: {
        type: Object as PropType<any>,
        required: true,
    },
    sintomas: {
        type: Array as PropType<any[]>,
        required: true,
    },
    ultima_presion: {
        type: Object as PropType<any>,
        required: false,
    },
    ultima_temperatura: {
        type: Object as PropType<any>,
        required: false,
    },
    ultima_frecuencia: {
        type: Object as PropType<any>,
        required: false,
    },
    historial_presion: {
        type: Array as PropType<any[]>,
        required: false,
    },
    historial_temperatura: {
        type: Array as PropType<any[]>,
        required: false,
    },
    historial_frecuencia: {
        type: Array as PropType<any[]>,
        required: false,
    },
});
</script>

<template>
    <Head :title="`Monitoreo de ${gestante.name} ${gestante.apellidos || ''}`" />
    <AppLayout>
        <div class="flex flex-col gap-4 p-4">
            <h2 class="mb-2 text-2xl font-bold">Monitoreo de {{ gestante.name }} {{ gestante.apellidos }}</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Columna de Síntomas -->
                <ListaSintomas :sintomas="sintomas" />
                <!-- Columna de Seguimiento de salud (más ancha) -->
                <SeguimientoSalud
                    :ultima-presion="ultima_presion"
                    :ultima-temperatura="ultima_temperatura"
                    :ultima-frecuencia="ultima_frecuencia"
                    :historial-presion="historial_presion"
                    :historial-temperatura="historial_temperatura"
                    :historial-frecuencia="historial_frecuencia"
                />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.text-muted-foreground {
    color: #6b7280;
}
</style>
