<script setup lang="ts">
import ModalEnviarNotificacion from '@/components/ModalEnviarNotificacion.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, type PropType, ref } from 'vue';
import ListaSintomas from './componentes/ListaSintomas.vue';
import SeguimientoSalud from './componentes/SeguimientoSalud.vue';

const props = defineProps({
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

const whatsappMensaje = computed(() => `Hola ${props.gestante.name}, este es un mensaje de contacto desde la plataforma de monitoreo.`);
const whatsappLink = computed(() => {
    const telefono = props.gestante.telefono ? props.gestante.telefono.replace(/[^\d]/g, '') : '';
    return telefono ? `https://wa.me/51${telefono}?text=${encodeURIComponent(whatsappMensaje.value)}` : '#';
});

const mostrarModalNotificacion = ref(false);

async function enviarNotificacion({ titulo, descripcion }: { titulo: string; descripcion: string }) {
    try {
        const response = await fetch(`/api/v1/gestantes/${props.gestante.id}/notificar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ titulo, descripcion }),
        });
        const data = await response.json();
        if (response.ok) {
            alert('Notificación enviada correctamente');
        } else {
            alert('Error al enviar notificación: ' + (data.message || 'Error desconocido'));
        }
    } catch (e) {
        alert('Error de red al enviar notificación');
    }
}

// Lógica de riesgo/preriesgo igual que en Dashboard.vue
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
    return (
        !isGestanteRiesgosa(g) &&
        (isPresionPreRiesgosa(g.ultima_presion) || isFrecuenciaPreRiesgosa(g.ultima_frecuencia) || isTemperaturaPreRiesgosa(g.ultima_temperatura))
    );
}
</script>

<template>
    <Head :title="`Monitoreo de ${gestante.name} ${gestante.apellidos || ''}`" />
    <AppLayout>
        <div
            class="flex flex-col gap-4 p-4"
            :class="[isGestanteRiesgosa(props) ? 'animate-alarm-border' : isGestantePreRiesgosa(props) ? 'animate-alarm-border-yellow' : '']"
        >
            <h2 class="mb-2 text-2xl font-bold">Monitoreo de {{ gestante.name }} {{ gestante.apellidos }}</h2>
            <div class="mb-2 flex gap-2">
                <Button @click="() => (mostrarModalNotificacion = true)">Enviar notificación</Button>
                <Button as="a" :href="whatsappLink" target="_blank" rel="noopener noreferrer" variant="outline"> Contactarse </Button>
            </div>
            <ModalEnviarNotificacion
                v-if="mostrarModalNotificacion"
                :gestante-id="gestante.id"
                @enviar="
                    (data) => {
                        mostrarModalNotificacion = false;
                        enviarNotificacion(data);
                    }
                "
                @cancelar="
                    () => {
                        mostrarModalNotificacion = false;
                    }
                "
            />
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
