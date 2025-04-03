<script setup>
import { Button } from '@/components/ui/button';
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/components/ui/pagination';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    meta: {
        type: Object,
        default: null,
    },
});

const filteredLinks = computed(() => props.meta.links?.slice(1, -1));

const currentPage = ref(1);

watch(() => props.meta, () => {
    currentPage.value = props.meta?.current_page || 1;
}, { immediate: true });
</script>

<template>
    <Pagination v-if="meta" v-slot="{ page }" :items-per-page="meta.per_page" :total="meta.total" :sibling-count="1"
        show-edges :default-page="1">
        <PaginationList v-slot="{ items }" class="flex items-center justify-center gap-1">
            <PaginationFirst @click="router.visit(meta.first_page_url, { preserveState: true })" />
            <PaginationPrev @click="router.visit(meta.prev_page_url, { preserveState: true })" />

            <template v-for="(item, index) in items">
                <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                    <Button class="h-9 w-9 p-0" :variant="item.value === currentPage ? 'default' : 'outline'" as-child>
                        <Link preserve-scroll preserve-state prefetch cache-for="1m"
                            :href="route(route().current(), { ...route().params, page: item.value })">
                        {{ item.value }}
                        </Link>
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <PaginationNext @click="router.visit(meta.next_page_url, { preserveState: true })" />
            <PaginationLast @click="router.visit(meta.last_page_url, { preserveState: true })" />
        </PaginationList>
    </Pagination>
</template>
