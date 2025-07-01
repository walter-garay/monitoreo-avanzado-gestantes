<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/lib/dateUtils';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PropType } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface Sintoma {
    id: number;
    descripcion: string;
    severidad: string;
    hora_inicio: string;
}

interface GestanteResumen {
    id: number;
    nombre: string;
    apellidos: string;
    ultimos_sintomas: Sintoma[];
    ultima_presion: any;
    ultima_temperatura: any;
    ultima_frecuencia: any;
}

defineProps({
    resumen_gestantes: {
        type: Array as PropType<GestanteResumen[]>,
        required: true,
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="g in resumen_gestantes" :key="g.id" class="border">
                    <CardHeader>
                        <CardTitle class="text-lg">{{ g.nombre }} {{ g.apellidos }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-3">
                            <!-- Síntomas -->
                            <div class="flex gap-2">
                                <div
                                    v-for="sintoma in g.ultimos_sintomas"
                                    :key="sintoma.id"
                                    class="min-w-[120px] flex-1 rounded p-2 text-center text-xs"
                                    :class="{
                                        'bg-green-100 text-green-900': sintoma.severidad === 'leve',
                                        'bg-yellow-100 text-yellow-900': sintoma.severidad === 'moderada',
                                        'bg-red-100 text-red-900': sintoma.severidad === 'grave',
                                    }"
                                >
                                    <div class="font-semibold">{{ sintoma.descripcion }}</div>
                                    <div class="mb-1">
                                        <Badge
                                            variant="secondary"
                                            :class="{
                                                'bg-green-200 text-green-800': sintoma.severidad === 'leve',
                                                'bg-yellow-200 text-yellow-800': sintoma.severidad === 'moderada',
                                                'bg-red-200 text-red-800': sintoma.severidad === 'grave',
                                            }"
                                        >
                                            {{ sintoma.severidad }}
                                        </Badge>
                                    </div>
                                    <div class="text-muted-foreground text-[10px]">
                                        {{ formatDate(sintoma.hora_inicio, 'withTime') }}
                                    </div>
                                </div>
                                <div v-if="!g.ultimos_sintomas || g.ultimos_sintomas.length === 0" class="text-muted-foreground flex-1 text-center">
                                    Sin síntomas recientes
                                </div>
                            </div>
                            <!-- Signos vitales -->
                            <div class="mt-2 flex gap-2">
                                <Card class="flex-1 border-blue-200 bg-blue-50">
                                    <CardContent class="flex flex-col items-center p-2">
                                        <span class="font-semibold text-blue-900">Presión arterial</span>
                                        <span v-if="g.ultima_presion" class="text-lg"
                                            >{{ g.ultima_presion.valor_sistolica }}/{{ g.ultima_presion.valor_diastolica }} mmHg</span
                                        >
                                        <span v-else class="text-muted-foreground">Sin datos</span>
                                        <span v-if="g.ultima_presion" class="text-muted-foreground text-xs">{{
                                            formatDate(g.ultima_presion.hora_lectura, 'withTime')
                                        }}</span>
                                    </CardContent>
                                </Card>
                                <Card class="flex-1 border-green-200 bg-green-50">
                                    <CardContent class="flex flex-col items-center p-2">
                                        <span class="font-semibold text-green-900">Frecuencia cardiaca</span>
                                        <span v-if="g.ultima_frecuencia" class="text-lg">{{ g.ultima_frecuencia.valor }} lpm</span>
                                        <span v-else class="text-muted-foreground">Sin datos</span>
                                        <span v-if="g.ultima_frecuencia" class="text-muted-foreground text-xs">{{
                                            formatDate(g.ultima_frecuencia.hora_lectura, 'withTime')
                                        }}</span>
                                    </CardContent>
                                </Card>
                                <Card class="flex-1 border-yellow-200 bg-yellow-50">
                                    <CardContent class="flex flex-col items-center p-2">
                                        <span class="font-semibold text-yellow-900">Temperatura</span>
                                        <span v-if="g.ultima_temperatura" class="text-lg">{{ g.ultima_temperatura.valor }} °C</span>
                                        <span v-else class="text-muted-foreground">Sin datos</span>
                                        <span v-if="g.ultima_temperatura" class="text-muted-foreground text-xs">{{
                                            formatDate(g.ultima_temperatura.hora_lectura, 'withTime')
                                        }}</span>
                                    </CardContent>
                                </Card>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
