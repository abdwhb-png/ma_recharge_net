<template>
    <div class="flex flex-col md:flex-row gap-5 text-gray-600 text-2sm font-medium" :class="[
        sizeClass,
        showItemsSelection ? 'justify-between' : 'justify-center',
    ]">
        <!-- Items per page selection -->
        <div class="flex items-center gap-2 order-2 md:order-1" v-if="showItemsSelection">
            <Select :model-value="modelValue" @update:modelValue="$emit('update:modelValue', $event)"
                :disabled="loading || !paginated.total">
                <SelectTrigger class="w-40">
                    <SelectValue placeholder="Items per page" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="item in itemsPerPage" :key="item" :value="item">
                        {{ item }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <!-- <Select class="w-40" :default-value="modelValue" :options="itemsPerPage" editable
                @change="$emit('update:modelValue', $event.value)" :disabled="loading || !paginated.total" /> -->
        </div>

        <!-- Boutons de pagination -->
        <div class="flex justify-center items-center gap-4 order-1 md:order-2">
            <span data-datatable-info="true">{{ paginationInfo }}</span>
            <PaginationBtns :meta="paginated" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { LaravelPagination } from "@/types";
import PaginationBtns from "./PaginationBtns.vue";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

const emits = defineEmits(['update:modelValue', 'reset']);

const props = defineProps({
    modelValue: [String, Number],
    paginated: {
        type: Object as () => LaravelPagination<any>,
        required: false,
        default: {},
    },
    showItemsSelection: {
        type: Boolean,
        default: true,
    },
    maxVisiblePages: {
        type: Number,
        required: false,
        default: 5,
    },
    sizeClass: String,
});

const loading = ref(false);
const itemsPerPage = [10, 20, 30, 50, 100];
const currentPage = computed(() => props.paginated.current_page);

// Calcul des indices de début et de fin des éléments affichés
const from = computed(
    () => (currentPage.value - 1) * props.paginated.per_page + 1,
);
const to = computed(() =>
    Math.min(
        currentPage.value * props.paginated.per_page,
        props.paginated.total,
    ),
);
const paginationInfo = computed(
    () => `${from.value} to ${to.value} of ${props.paginated.total}`,
);

// Filtrer les liens de pagination pour exclure "Précédent" et "Suivant"
const filteredLinks = computed(() => props.paginated.links?.slice(1, -1));

// Limiter à 5 pages visibles à la fois
const visiblePageNumbers = computed(() => {
    let start = Math.max(
        currentPage.value - Math.floor(props.maxVisiblePages / 2),
        0,
    );
    let end = start + props.maxVisiblePages;

    return filteredLinks.value?.slice(start, end);
});
</script>
