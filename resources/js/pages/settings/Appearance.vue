<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Apariencia del sistema',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Apariencia del sistema" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Configurar apariencia" description="Actualiza la apariencia por defecto del sistema en tu cuenta." />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
