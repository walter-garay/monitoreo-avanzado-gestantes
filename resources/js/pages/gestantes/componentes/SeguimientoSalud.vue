<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { formatDate } from '@/lib/dateUtils';
import { CategoryScale, Chart as ChartJS, Legend, LinearScale, LineElement, PointElement, Title, Tooltip } from 'chart.js';
import { type PropType, computed } from 'vue';
import { Line } from 'vue-chartjs';

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale);

const props = defineProps({
    ultimaPresion: {
        type: Object as PropType<any>,
        required: false,
    },
    ultimaTemperatura: {
        type: Object as PropType<any>,
        required: false,
    },
    ultimaFrecuencia: {
        type: Object as PropType<any>,
        required: false,
    },
    historialPresion: {
        type: Array as PropType<any[]>,
        required: false,
    },
    historialTemperatura: {
        type: Array as PropType<any[]>,
        required: false,
    },
    historialFrecuencia: {
        type: Array as PropType<any[]>,
        required: false,
    },
});

// Preparar datos para los gráficos
const presionData = computed(() => {
    if (!props.historialPresion) return null;
    const labels = props.historialPresion
        .slice()
        .reverse()
        .map((p) => formatDate(p.hora_lectura, 'withTime'));
    return {
        labels,
        datasets: [
            {
                label: 'Sistólica',
                data: props.historialPresion
                    .slice()
                    .reverse()
                    .map((p) => p.valor_sistolica),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                tension: 0.3,
            },
            {
                label: 'Diastólica',
                data: props.historialPresion
                    .slice()
                    .reverse()
                    .map((p) => p.valor_diastolica),
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.2)',
                tension: 0.3,
            },
        ],
    };
});
const temperaturaData = computed(() => {
    if (!props.historialTemperatura) return null;
    const labels = props.historialTemperatura
        .slice()
        .reverse()
        .map((t) => formatDate(t.hora_lectura, 'withTime'));
    return {
        labels,
        datasets: [
            {
                label: 'Temperatura (°C)',
                data: props.historialTemperatura
                    .slice()
                    .reverse()
                    .map((t) => t.valor),
                borderColor: 'rgb(245, 158, 11)',
                backgroundColor: 'rgba(245, 158, 11, 0.2)',
                tension: 0.3,
            },
        ],
    };
});
const frecuenciaData = computed(() => {
    if (!props.historialFrecuencia) return null;
    const labels = props.historialFrecuencia
        .slice()
        .reverse()
        .map((f) => formatDate(f.hora_lectura, 'withTime'));
    return {
        labels,
        datasets: [
            {
                label: 'Frecuencia (lpm)',
                data: props.historialFrecuencia
                    .slice()
                    .reverse()
                    .map((f) => f.valor),
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                tension: 0.3,
            },
        ],
    };
});

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { position: 'top' as const },
        title: { display: false },
    },
    scales: {
        x: { display: true, title: { display: false } },
        y: { display: true, title: { display: false } },
    },
};

// Lógica de riesgo/preriesgo para el punto parpadeante
function isPresionRiesgosa(p: any) {
    if (!p) return false;
    const s = Number(p.valor_sistolica);
    const d = Number(p.valor_diastolica);
    return s >= 140 || d >= 90;
}
function isPresionPreRiesgosa(p: any) {
    if (!p) return false;
    const s = Number(p.valor_sistolica);
    const d = Number(p.valor_diastolica);
    return (s >= 121 && s <= 139) || (d >= 81 && d <= 89);
}
function isFrecuenciaRiesgosa(f: any) {
    if (!f) return false;
    const v = Number(f.valor);
    return v > 110 || v < 50;
}
function isFrecuenciaPreRiesgosa(f: any) {
    if (!f) return false;
    const v = Number(f.valor);
    return v >= 101 && v <= 110;
}
function isTemperaturaRiesgosa(t: any) {
    if (!t) return false;
    const v = Number(t.valor);
    return v > 38.0 || v < 36.0;
}
function isTemperaturaPreRiesgosa(t: any) {
    if (!t) return false;
    const v = Number(t.valor);
    return v >= 37.6 && v <= 38.0;
}
const presionStatus = computed(() => {
    if (isPresionRiesgosa(props.ultimaPresion)) return 'riesgo';
    if (isPresionPreRiesgosa(props.ultimaPresion)) return 'preriesgo';
    return 'normal';
});
const frecuenciaStatus = computed(() => {
    if (isFrecuenciaRiesgosa(props.ultimaFrecuencia)) return 'riesgo';
    if (isFrecuenciaPreRiesgosa(props.ultimaFrecuencia)) return 'preriesgo';
    return 'normal';
});
const temperaturaStatus = computed(() => {
    if (isTemperaturaRiesgosa(props.ultimaTemperatura)) return 'riesgo';
    if (isTemperaturaPreRiesgosa(props.ultimaTemperatura)) return 'preriesgo';
    return 'normal';
});
</script>

<template>
    <Card class="col-span-1 md:col-span-2">
        <CardHeader>
            <CardTitle>Seguimiento de salud</CardTitle>
        </CardHeader>
        <CardContent>
            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                <Card v-if="ultimaPresion" class="border">
                    <CardHeader>
                        <CardTitle class="text-base">Presión arterial</CardTitle>
                        <CardDescription>{{ ultimaPresion?.hora_lectura ? formatDate(ultimaPresion.hora_lectura, 'withTime') : '' }}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <span class="font-semibold">{{ ultimaPresion.valor_sistolica }}/{{ ultimaPresion.valor_diastolica }} mmHg</span>
                    </CardContent>
                </Card>
                <Card v-if="ultimaFrecuencia" class="border">
                    <CardHeader>
                        <CardTitle class="text-base">Frecuencia cardiaca</CardTitle>
                        <CardDescription>{{
                            ultimaFrecuencia?.hora_lectura ? formatDate(ultimaFrecuencia.hora_lectura, 'withTime') : ''
                        }}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <span class="font-semibold">{{ ultimaFrecuencia.valor }} lpm</span>
                    </CardContent>
                </Card>
                <Card v-if="ultimaTemperatura" class="border">
                    <CardHeader>
                        <CardTitle class="text-base">Temperatura corporal</CardTitle>
                        <CardDescription>{{
                            ultimaTemperatura?.hora_lectura ? formatDate(ultimaTemperatura.hora_lectura, 'withTime') : ''
                        }}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <span class="font-semibold">{{ ultimaTemperatura.valor }} °C</span>
                    </CardContent>
                </Card>
            </div>
            <div class="mb-4 flex flex-col gap-4">
                <div v-if="presionData" class="relative rounded bg-white p-2 shadow">
                    <span
                        class="absolute top-2 right-2 h-3 w-3 animate-pulse rounded-full"
                        :class="{
                            'bg-red-500': presionStatus === 'riesgo',
                            'bg-yellow-400': presionStatus === 'preriesgo',
                            'bg-green-500': presionStatus === 'normal',
                        }"
                    ></span>
                    <Line :data="presionData" :options="chartOptions" />
                </div>
                <div v-if="frecuenciaData" class="relative rounded bg-white p-2 shadow">
                    <span
                        class="absolute top-2 right-2 h-3 w-3 animate-pulse rounded-full"
                        :class="{
                            'bg-red-500': frecuenciaStatus === 'riesgo',
                            'bg-yellow-400': frecuenciaStatus === 'preriesgo',
                            'bg-green-500': frecuenciaStatus === 'normal',
                        }"
                    ></span>
                    <Line :data="frecuenciaData" :options="chartOptions" />
                </div>
                <div v-if="temperaturaData" class="relative rounded bg-white p-2 shadow">
                    <span
                        class="absolute top-2 right-2 h-3 w-3 animate-pulse rounded-full"
                        :class="{
                            'bg-red-500': temperaturaStatus === 'riesgo',
                            'bg-yellow-400': temperaturaStatus === 'preriesgo',
                            'bg-green-500': temperaturaStatus === 'normal',
                        }"
                    ></span>
                    <Line :data="temperaturaData" :options="chartOptions" />
                </div>
            </div>
        </CardContent>
    </Card>
</template>
