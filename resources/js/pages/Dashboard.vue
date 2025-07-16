<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/lib/dateUtils';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Activity, HeartPulse, Thermometer } from 'lucide-vue-next';
import { PropType, computed } from 'vue';

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

const props = defineProps({
    resumen_gestantes: {
        type: Array as PropType<GestanteResumen[]>,
        required: true,
    },
});

function isPresionRiesgosa(p: any) {
    if (!p) return false;
    const s = Number(p.valor_sistolica);
    const d = Number(p.valor_diastolica);
    return s >= 140 || d >= 90;
}

function isFrecuenciaRiesgosa(f: any) {
    if (!f) return false;
    const v = Number(f.valor);
    return v > 110 || v < 50;
}

function isTemperaturaRiesgosa(t: any) {
    if (!t) return false;
    const v = Number(t.valor);
    return v > 38.0 || v < 36.0;
}

function isGestanteRiesgosa(g: any) {
    return isPresionRiesgosa(g.ultima_presion) || isFrecuenciaRiesgosa(g.ultima_frecuencia) || isTemperaturaRiesgosa(g.ultima_temperatura);
}

function isPresionPreRiesgosa(p: any) {
    if (!p) return false;
    const s = Number(p.valor_sistolica);
    const d = Number(p.valor_diastolica);
    return (s >= 121 && s <= 139) || (d >= 81 && d <= 89);
}

function isFrecuenciaPreRiesgosa(f: any) {
    if (!f) return false;
    const v = Number(f.valor);
    return v >= 101 && v <= 110;
}

function isTemperaturaPreRiesgosa(t: any) {
    if (!t) return false;
    const v = Number(t.valor);
    return v >= 37.6 && v <= 38.0;
}

function isGestantePreRiesgosa(g: any) {
    // No debe ser riesgosa, pero sí pre-riesgosa
    return (
        !isGestanteRiesgosa(g) &&
        (isPresionPreRiesgosa(g.ultima_presion) || isFrecuenciaPreRiesgosa(g.ultima_frecuencia) || isTemperaturaPreRiesgosa(g.ultima_temperatura))
    );
}

const gestantesOrdenadas = computed(() => {
    const riesgosas: any[] = [];
    const preRiesgosas: any[] = [];
    const normales: any[] = [];
    for (const g of props.resumen_gestantes) {
        if (isGestanteRiesgosa(g)) {
            riesgosas.push(g);
        } else if (isGestantePreRiesgosa(g)) {
            preRiesgosas.push(g);
        } else {
            normales.push(g);
        }
    }
    return [...riesgosas, ...preRiesgosas, ...normales];
});

// Contadores para resumen
const resumenContadores = computed(() => {
    let riesgo = 0;
    let preRiesgo = 0;
    let normal = 0;
    for (const g of props.resumen_gestantes) {
        if (isGestanteRiesgosa(g)) {
            riesgo++;
        } else if (isGestantePreRiesgosa(g)) {
            preRiesgo++;
        } else {
            normal++;
        }
    }
    return { riesgo, preRiesgo, normal };
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <!-- Resumen superior -->
            <div class="mb-2 flex flex-wrap gap-4">
                <div class="flex items-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                    En riesgo: {{ resumenContadores.riesgo }}
                </div>
                <div class="flex items-center gap-2 rounded-lg bg-yellow-50 px-4 py-2 text-sm font-semibold text-yellow-700">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-yellow-400"></span>
                    Alerta: {{ resumenContadores.preRiesgo }}
                </div>
                <div class="flex items-center gap-2 rounded-lg bg-green-50 px-4 py-2 text-sm font-semibold text-green-700">
                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                    Normal: {{ resumenContadores.normal }}
                </div>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="g in gestantesOrdenadas"
                    :key="g.id"
                    :class="[
                        'w-full max-w-full cursor-pointer border transition duration-200 hover:-translate-y-1 hover:border-blue-400 hover:shadow-lg',
                        isGestanteRiesgosa(g) ? 'animate-alarm-border' : isGestantePreRiesgosa(g) ? 'animate-alarm-border-yellow' : '',
                    ]"
                    @click="router.get(route('gestantes.monitor', g.id))"
                >
                    <CardHeader>
                        <CardTitle class="text-lg">{{ g.nombre }} {{ g.apellidos }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-3">
                            <!-- Signos vitales -->
                            <div class="mb-2 flex w-full max-w-full gap-2">
                                <Card class="w-full max-w-full flex-1 rounded-lg border border-gray-100 bg-white shadow-sm">
                                    <CardContent class="flex flex-col items-center gap-1 p-1">
                                        <span class="flex items-center gap-1 text-xs font-semibold text-blue-600">
                                            <Activity class="h-4 w-4" />
                                            Presión
                                        </span>
                                        <span v-if="g.ultima_presion" class="text-base font-medium text-gray-800"
                                            >{{ g.ultima_presion.valor_sistolica }}/{{ g.ultima_presion.valor_diastolica }}</span
                                        >
                                        <span v-else class="text-muted-foreground text-xs">Sin datos</span>
                                        <span v-if="g.ultima_presion" class="text-muted-foreground text-[10px]">{{
                                            formatDate(g.ultima_presion.hora_lectura, 'withTime')
                                        }}</span>
                                    </CardContent>
                                </Card>
                                <Card class="w-full max-w-full flex-1 rounded-lg border border-gray-100 bg-white shadow-sm">
                                    <CardContent class="flex flex-col items-center gap-1 p-1">
                                        <span class="flex items-center gap-1 text-xs font-semibold text-pink-600">
                                            <HeartPulse class="h-4 w-4" />
                                            F. cardiaca
                                        </span>
                                        <span v-if="g.ultima_frecuencia" class="text-base font-medium text-gray-800">{{
                                            g.ultima_frecuencia.valor
                                        }}</span>
                                        <span v-else class="text-muted-foreground text-xs">Sin datos</span>
                                        <span v-if="g.ultima_frecuencia" class="text-muted-foreground text-[10px]">{{
                                            formatDate(g.ultima_frecuencia.hora_lectura, 'withTime')
                                        }}</span>
                                    </CardContent>
                                </Card>
                                <Card class="w-full max-w-full flex-1 rounded-lg border border-gray-100 bg-white shadow-sm">
                                    <CardContent class="flex flex-col items-center gap-1 p-1">
                                        <span class="flex items-center gap-1 text-xs font-semibold text-yellow-600">
                                            <Thermometer class="h-4 w-4" />
                                            Temp.
                                        </span>
                                        <span v-if="g.ultima_temperatura" class="text-base font-medium text-gray-800">{{
                                            g.ultima_temperatura.valor
                                        }}</span>
                                        <span v-else class="text-muted-foreground text-xs">Sin datos</span>
                                        <span v-if="g.ultima_temperatura" class="text-muted-foreground text-[10px]">{{
                                            formatDate(g.ultima_temperatura.hora_lectura, 'withTime')
                                        }}</span>
                                    </CardContent>
                                </Card>
                            </div>
                            <!-- Síntomas -->
                            <div class="flex w-full max-w-full gap-2">
                                <div
                                    v-for="sintoma in g.ultimos_sintomas"
                                    :key="sintoma.id"
                                    class="min-w-[120px] flex-1 rounded-lg p-2 text-center text-xs"
                                    :class="{
                                        'bg-green-50 text-green-900': sintoma.severidad === 'leve',
                                        'bg-yellow-50 text-yellow-900': sintoma.severidad === 'moderada',
                                        'bg-red-50 text-red-900': sintoma.severidad === 'grave',
                                    }"
                                >
                                    <div class="line-clamp-2 font-semibold">{{ sintoma.descripcion }}</div>
                                    <div class="text-muted-foreground text-[10px]">
                                        {{ formatDate(sintoma.hora_inicio, 'withTime') }}
                                    </div>
                                </div>
                                <div v-if="!g.ultimos_sintomas || g.ultimos_sintomas.length === 0" class="text-muted-foreground flex-1 text-center">
                                    Sin síntomas recientes
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style>
@keyframes alarm-border {
    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        border-color: #dc2626;
    }
    50% {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.3);
        border-color: #f87171;
    }
}
.animate-alarm-border {
    animation: alarm-border 1s infinite;
    border-color: #dc2626 !important;
}

@keyframes alarm-border-yellow {
    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7);
        border-color: #facc15;
    }
    50% {
        box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.3);
        border-color: #fde047;
    }
}
.animate-alarm-border-yellow {
    animation: alarm-border-yellow 1s infinite;
    border-color: #facc15 !important;
}
</style>
