<script setup lang="ts">
import Tokens from './partials/Tokens.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Token Manager',
        href: '/token-manager',
    },
];
</script>

<template>

    <Head title="Token Manager" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-5 md:px-20">
            <Tokens :tokens="tokens" :available-permissions="availablePermissions"
                :default-permissions="defaultPermissions" />
        </div>
    </AppLayout>
</template>
