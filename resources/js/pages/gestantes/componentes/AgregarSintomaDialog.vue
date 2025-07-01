<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { ref } from 'vue';
import * as z from 'zod';

const emit = defineEmits(['submit']);

const formSchema = toTypedSchema(
    z.object({
        descripcion: z.string().min(1, 'La descripción es obligatoria'),
        severidad: z.string().min(1, 'La severidad es obligatoria'),
        hora_inicio: z.string().min(1, 'La hora de inicio es obligatoria'),
        hora_fin: z.string().nullable().optional(),
    }),
);

const { handleSubmit, resetForm } = useForm({
    validationSchema: formSchema,
    initialValues: {
        descripcion: '',
        severidad: 'leve',
        hora_inicio: '',
        hora_fin: '',
    },
});

const loading = ref(false);

function onSubmit(e: any) {
    handleSubmit((values) => {
        loading.value = true;
        emit('submit', values);
        loading.value = false;
        resetForm();
    })(e);
}
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="outline">Agregar síntoma</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Agregar síntoma</DialogTitle>
            </DialogHeader>
            <Form @submit="onSubmit" class="flex flex-col gap-4">
                <FormField name="descripcion">
                    <FormItem>
                        <FormLabel>Descripción</FormLabel>
                        <FormControl>
                            <Input type="text" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField name="severidad">
                    <FormItem>
                        <FormLabel>Severidad</FormLabel>
                        <FormControl>
                            <Select>
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Selecciona severidad" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="leve">Leve</SelectItem>
                                    <SelectItem value="moderada">Moderada</SelectItem>
                                    <SelectItem value="grave">Grave</SelectItem>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField name="hora_inicio">
                    <FormItem>
                        <FormLabel>Hora de inicio</FormLabel>
                        <FormControl>
                            <Input type="datetime-local" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField name="hora_fin">
                    <FormItem>
                        <FormLabel>Hora de fin</FormLabel>
                        <FormControl>
                            <Input type="datetime-local" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <DialogFooter>
                    <Button type="submit" :disabled="loading">Guardar</Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
input,
select {
    border: 1px solid #d1d5db;
}
</style>
