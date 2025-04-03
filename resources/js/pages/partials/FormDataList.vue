<script setup lang="ts">
import MyPagination from '@/components/appli/MyPagination.vue';
import SearchFilter from '@/components/appli/SearchFilter.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import {
    DropdownMenuRadioGroup, DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { router } from '@inertiajs/vue3';
import { LaravelPagination } from '@/types';
import { throttle, pickBy, mapValues } from 'lodash';
import { Info, Trash2 } from 'lucide-vue-next';
import { reactive, ref, watch } from 'vue';
import { useToast } from '@/components/ui/toast';

const props = defineProps({
    paginated: {
        type: Object as () => LaravelPagination<any>,
        required: false,
        default: null,
    },
    dataFilters: {
        type: Object,
        required: false,
        default: {},
    },
    filterKey: String,
});

const loading = ref(false);
const data = ref<any[]>([]);
const metadata = ref<any[]>([]);
const { toast } = useToast();

const form = reactive({
    search: props.dataFilters.search,
    per_page: props.paginated?.per_page,
    [props.filterKey]: props.dataFilters[props.filterKey],
});

const reset = () => {
    Object.assign(
        form,
        mapValues(form, () => null),
    );
};

watch(() => props.paginated, () => {
    const { data: newData, ...meta } = props.paginated;
    data.value = newData;
    metadata.value = meta;
}, { immediate: true });

watch(
    form,
    throttle(() => {
        const url = route(route().current());
        router.get(url, pickBy(form), {
            preserveState: true,
            onStart: () => (loading.value = true),
            onFinish: () => (loading.value = false),
        });
        // emits('searched', pickBy(form.value, (value) => value !== null && value !== ''));
    }, 150),
    { deep: true },
);
</script>

<template>
    <div class="w-full">
        <div class="flex items-center py-4">
            <SearchFilter class="mr-4 w-full max-w-md" v-model="form.search" :loading="loading"
                :data-filters="dataFilters" :filter-key="filterKey" @reset="reset">
                <template #filterContent>
                    <DropdownMenuRadioGroup v-model="form[filterKey]">
                        <slot name="filterContent" />
                    </DropdownMenuRadioGroup>
                </template>
            </SearchFilter>
        </div>
        <div class="rounded-md border">
            <Table v-if="paginated">
                <!-- <TableCaption>{{ paginated.total }} records</TableCaption> -->
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]"> ## </TableHead>
                        <TableHead>Data</TableHead>
                        <TableHead>Entries</TableHead>
                        <TableHead>Location</TableHead>
                        <TableHead class="text-right"> Created At </TableHead>
                    </TableRow>
                    <TableRow>
                        <TableCell colspan="5">
                            <MyPagination :paginated="paginated" v-model="form.per_page" />
                        </TableCell>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(item, index) in paginated.data" :key="item.id || index">
                        <TableCell class="flex flex-col justify-center space-y-2">
                            <span class="font-bold">{{ '#' + item.id }}</span>
                            <a :href="'https://whatismyipaddress.com/ip/' + item.ip_address" target="_blank"
                                class="text-blue-500 hover:underline">{{
                                    item.ip_address || '--' }}</a>

                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="outline" size="icon" class="text-red-500 hover:text-red-700">
                                        <Trash2 :size="16" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent class="w-56">
                                    <DropdownMenuItem
                                        @click="router.delete(route('form-data.destroy', item.id), { preserveScroll: true, onSuccess: (page) => toast({ title: 'Deleted', description: page.props.flash.success }) })">
                                        <span>Delete</span>
                                        <DropdownMenuShortcut>X</DropdownMenuShortcut>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>

                        </TableCell>
                        <TableCell class="font-medium">
                            <pre>{{ item.data }}</pre>
                        </TableCell>
                        <TableCell>
                            <pre>{{ item.entries }}</pre>
                        </TableCell>
                        <TableCell>
                            <pre>{{ item.location }}</pre>
                        </TableCell>
                        <TableCell class="text-right">
                            {{ item.created_at }}
                        </TableCell>
                    </TableRow>
                </TableBody>
                <TableFooter>
                    <TableRow>
                        <TableCell colspan="5">
                            <MyPagination :paginated="paginated" v-model="form.per_page" />
                        </TableCell>
                    </TableRow>
                </TableFooter>
            </Table>
            <Alert v-else>
                <Info class="h-4 w-4" />
                <AlertTitle>No form data records found!</AlertTitle>
                <AlertDescription>
                    <pre>{{ paginated }}</pre>
                </AlertDescription>
            </Alert>
        </div>
    </div>
</template>
