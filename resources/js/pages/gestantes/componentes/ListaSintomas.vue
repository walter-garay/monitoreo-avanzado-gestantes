<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { formatDate } from '@/lib/dateUtils';
import { type PropType } from 'vue';
import AgregarSintomaDialog from './AgregarSintomaDialog.vue';

defineProps({
    sintomas: {
        type: Array as PropType<any[]>,
        required: true,
    },
});
</script>

<template>
    <Card class="col-span-1">
        <CardHeader>
            <CardTitle>Síntomas</CardTitle>
        </CardHeader>
        <CardContent>
            <AgregarSintomaDialog
                @submit="
                    (nuevoSintoma) => {
                        console.log('Nuevo síntoma:', nuevoSintoma); /* Aquí va la lógica de guardado */
                    }
                "
            />
            <div v-if="sintomas.length > 0" class="mt-4 flex flex-col gap-3">
                <Card v-for="sintoma in sintomas" :key="sintoma.id" class="border bg-gray-50">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-base font-medium">{{ sintoma.descripcion }}</CardTitle>
                        <CardDescription>{{ formatDate(sintoma.hora_inicio, 'withTime') }}</CardDescription>
                    </CardHeader>
                    <CardContent class="pt-0">
                        <span class="text-sm">Severidad:</span>
                        <Badge
                            variant="secondary"
                            class="ml-2"
                            :class="{
                                'bg-green-200 text-green-800': sintoma.severidad === 'leve',
                                'bg-yellow-200 text-yellow-800': sintoma.severidad === 'moderada',
                                'bg-red-200 text-red-800': sintoma.severidad === 'grave',
                            }"
                        >
                            {{ sintoma.severidad }}
                        </Badge>
                    </CardContent>
                </Card>
            </div>
            <div v-else class="text-muted-foreground mt-4">No se han registrado síntomas recientes.</div>
        </CardContent>
    </Card>
</template>
