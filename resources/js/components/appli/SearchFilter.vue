<template>
    <div class="5 flex flex-col space-y-0">
        <div class="flex items-center">
            <div class="flex w-full rounded bg-white p-2 shadow">
                <LoaderCircle class="animate-spin" v-if="loading" />
                <DropdownMenu v-if="filterKey">
                    <DropdownMenuTrigger>
                        <Button :variant="dataFilters[filterKey] ? 'default' : 'secondary'">
                            {{ dataFilters[filterKey] ? 'Filtered' : 'Filter' }}
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <DropdownMenuLabel>Filter by : {{ filterKey }}</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <slot name="filterContent" />
                    </DropdownMenuContent>
                </DropdownMenu>
                <input class="focus:shadow-outline relative w-full rounded-r px-6 py-2" autocomplete="off" type="text"
                    name="search" placeholder="Search…" :value="modelValue" ref="searchInput"
                    @input="$emit('update:modelValue', $event.target.value)" />
            </div>
            <Button variant="outline" class="ml-3" type="button" @click="emits('reset')">
                <X v-if="modelValue" :size="16" />
                <span class="">Reset</span>
            </Button>
        </div>
    </div>
</template>

<script setup>
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { LoaderCircle, X } from 'lucide-vue-next';
import { ref } from 'vue';

const emits = defineEmits(['update:modelValue', 'reset']);
const props = defineProps({
    modelValue: String,
    maxWidth: {
        type: Number,
        default: 300,
    },
    loading: Boolean,
    filterKey: String,
    dataFilters: Object,
});

const searchInput = ref();
</script>
