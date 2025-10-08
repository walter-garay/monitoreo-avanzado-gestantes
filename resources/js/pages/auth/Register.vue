<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    accept_privacy: false,
});

const submit = () => {
    if (!form.accept_privacy) {
        form.setError('accept_privacy', 'Debe aceptar los Términos y Condiciones y la Política de Privacidad para continuar.');
        return;
    }
    form.clearErrors('accept_privacy');
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Crear una cuenta" description="Ingresa tus datos para crear tu cuenta">
        <Head title="Registro" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Nombre</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        v-model="form.name"
                        placeholder="Nombre completo"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="correo@ejemplo.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Contraseña</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Contraseña"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirmar contraseña</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirmar contraseña"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <div class="flex items-start gap-3">
                    <input
                        id="accept_privacy"
                        type="checkbox"
                        class="mt-1 h-4 w-4 rounded border-neutral-300 text-[#f53003] focus:ring-[#f53003] dark:border-neutral-700"
                        v-model="form.accept_privacy"
                        :tabindex="5"
                        required
                    />
                    <Label for="accept_privacy" class="text-sm leading-5 whitespace-normal break-words flex-3">
                        <p>Acepto los <a :href="route('terms')" target="_blank" class="text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]">Términos y Condiciones</a> y la <a :href="route('privacy-policy')" target="_blank" class="text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]">Política de Privacidad</a>.</p>

                    </Label>
                </div>
                <InputError :message="form.errors.accept_privacy" />

                <Button type="submit" class="mt-2 w-full" tabindex="6" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Crear cuenta
                </Button>
            </div>

            <div class="text-muted-foreground text-center text-sm">
                ¿Ya tienes una cuenta?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="7">Iniciar sesión</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
