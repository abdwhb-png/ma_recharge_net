<script setup lang="ts">
import { DropdownMenuRadioItem } from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArrowDown01, ArrowDown10 } from 'lucide-vue-next';
import FormDataList from './partials/FormDataList.vue';
import SettingForm from './partials/SettingForm.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps({
    setting: Object,
    form_datas: [Array, Object],
    filters: [Array, Object],
});
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="col-span-1 md:order-2">
                <SettingForm :setting="setting" class="sticky top-4" />
            </div>
            <div class="col-span-3 lg:col-span-2 ">
                <FormDataList :paginated="form_datas" filter-key="sort" :data-filters="filters">
                    <template #filterContent>
                        <DropdownMenuRadioItem value="asc" class="capitalize"> ASC
                            <ArrowDown01 :size="16" />
                        </DropdownMenuRadioItem>
                        <DropdownMenuRadioItem value="desc" class="capitalize"> DESC
                            <ArrowDown10 :size="16" />
                        </DropdownMenuRadioItem>
                    </template>
                </FormDataList>
            </div>
        </div>
    </AppLayout>
</template>
