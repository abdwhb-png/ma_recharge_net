<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import Toaster from '@/components/ui/toast/Toaster.vue'
import { Button } from '@/components/ui/button'
import type { BreadcrumbItemType } from '@/types';
import { router } from '@inertiajs/vue3';
import { RefreshCcw } from 'lucide-vue-next';
import { useToast } from '@/components/ui/toast';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const { toast } = useToast();

const onReload = () => {
    router.reload({
        onSuccess: () => toast({ title: 'Page reloaded', description: 'The page has been reloaded' }),
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toaster />
        <div class="flex justify-center my-3 animate-pulse">
            <Button variant="secondary" @click="onReload()">
                <RefreshCcw /> Reload
            </Button>
        </div>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <slot />
        </div>
    </AppLayout>
</template>
