<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import Toaster from '@/components/ui/toast/Toaster.vue'
import { Button } from '@/components/ui/button'
import type { BreadcrumbItemType } from '@/types';
import { router } from '@inertiajs/vue3';
import { RefreshCcw } from 'lucide-vue-next';
import { useToast } from '@/components/ui/toast';
import { ref } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const { toast } = useToast();
const reloading = ref(false);

const onReload = () => {
    reloading.value = true;
    router.reload({
        onSuccess: () => toast({ title: 'Page reloaded', description: 'The page has been reloaded' }),
        onFinish: () => (reloading.value = false),
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toaster />
        <div class="flex justify-center my-3" :class="{ 'animate-pulse': reloading }">
            <Button variant="secondary" @click="onReload()" :disabled="reloading">
                <RefreshCcw :class="{ 'animate-spin': reloading }" />
                Reload
            </Button>
        </div>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <slot />
        </div>
    </AppLayout>
</template>
