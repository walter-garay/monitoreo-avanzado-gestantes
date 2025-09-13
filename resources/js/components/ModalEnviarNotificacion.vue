<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { ref } from 'vue';

defineProps<{ gestanteId: number; loading?: boolean }>();

const emit = defineEmits(['enviar', 'cancelar']);
const titulo = ref('');
const descripcion = ref('');
const errorTitulo = ref('');
const errorDescripcion = ref('');

function handleEnviar() {
    let valido = true;
    if (!titulo.value.trim()) {
        errorTitulo.value = 'El título es obligatorio';
        valido = false;
    } else {
        errorTitulo.value = '';
    }
    if (!descripcion.value.trim()) {
        errorDescripcion.value = 'La descripción es obligatoria';
        valido = false;
    } else {
        errorDescripcion.value = '';
    }
    if (!valido) return;
    emit('enviar', { titulo: titulo.value, descripcion: descripcion.value });
    titulo.value = '';
    descripcion.value = '';
    errorTitulo.value = '';
    errorDescripcion.value = '';
}
function handleCancelar() {
    emit('cancelar');
    titulo.value = '';
    descripcion.value = '';
    errorTitulo.value = '';
    errorDescripcion.value = '';
}
</script>

<template>
    <Dialog open>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Enviar notificación</DialogTitle>
            </DialogHeader>
            <div class="mb-4">
                <label for="titulo" class="mb-1 block text-sm font-medium">Título</label>
                <Input id="titulo" v-model="titulo" placeholder="Título de la notificación" />
                <InputError :message="errorTitulo" />
            </div>
            <div class="mb-4">
                <label for="descripcion" class="mb-1 block text-sm font-medium">Descripción</label>
                <Input id="descripcion" v-model="descripcion" placeholder="Escribe el mensaje..." />
                <InputError :message="errorDescripcion" />
            </div>
            <DialogFooter class="flex justify-end gap-2">
                <Button variant="secondary" @click="handleCancelar" :disabled="loading">Cancelar</Button>
                <Button @click="handleEnviar" :disabled="loading">
                    <span v-if="loading">Enviando...</span>
                    <span v-else>Enviar</span>
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
