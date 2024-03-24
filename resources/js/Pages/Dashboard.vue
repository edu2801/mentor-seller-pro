<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps(['advertises']);

const headers = [
    { title: 'Anúncio', key: 'title' },
    { title: 'Status', key: 'status' },
    {
        title: 'Nota',
        key: 'geral_grade',
        value: item => item?.geral_grade?.toLocaleString('pt-br', { style: 'percent', minimumFractionDigits: 2, maximumFractionDigits: 2 }),
    },
    { title: 'Atualização', key: 'updated_at', value: item => new Date(item?.updated_at).toLocaleString("pt-br")},
    { title: 'Ações', key: 'actions', align: 'end' }
]

const search = ref('')

const filteredAdvertises = ref([]);

watch(search, () => {
    if (!search.value) {
        filteredAdvertises.value = props.advertises;
        return;
    }

    const searchTerm = search.value.toLowerCase();
    filteredAdvertises.value = props.advertises.filter(ad => {
        return Object.values(ad).some(val => {
            if (typeof val === 'string' || val instanceof String) {
                return val.toLowerCase().includes(searchTerm);
            }

            if (typeof val === 'number' || val instanceof Number) {
                return val.toString().toLowerCase().includes(searchTerm);
            }
            return false;
        });
    });
}, { immediate: true });
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- <div class="mt-5 flex justify-center items-center">
            <div class="p-4 bg-white shadow-lg rounded-2xl mr-3">
                <div class="flex items-center">
                    <span class="relative p-4 bg-orange-200 rounded-xl">
                        <svg width="40" fill="currentColor" height="40"
                            class="absolute h-4 text-orange-500 transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1362 1185q0 153-99.5 263.5t-258.5 136.5v175q0 14-9 23t-23 9h-135q-13 0-22.5-9.5t-9.5-22.5v-175q-66-9-127.5-31t-101.5-44.5-74-48-46.5-37.5-17.5-18q-17-21-2-41l103-135q7-10 23-12 15-2 24 9l2 2q113 99 243 125 37 8 74 8 81 0 142.5-43t61.5-122q0-28-15-53t-33.5-42-58.5-37.5-66-32-80-32.5q-39-16-61.5-25t-61.5-26.5-62.5-31-56.5-35.5-53.5-42.5-43.5-49-35.5-58-21-66.5-8.5-78q0-138 98-242t255-134v-180q0-13 9.5-22.5t22.5-9.5h135q14 0 23 9t9 23v176q57 6 110.5 23t87 33.5 63.5 37.5 39 29 15 14q17 18 5 38l-81 146q-8 15-23 16-14 3-27-7-3-3-14.5-12t-39-26.5-58.5-32-74.5-26-85.5-11.5q-95 0-155 43t-60 111q0 26 8.5 48t29.5 41.5 39.5 33 56 31 60.5 27 70 27.5q53 20 81 31.5t76 35 75.5 42.5 62 50 53 63.5 31.5 76.5 13 94z">
                            </path>
                        </svg>
                    </span>
                    <p class="ml-2 text-black text-md">
                        Anuncios
                    </p>
                </div>
                <div class="flex flex-col justify-start">
                    <p class="my-4 text-4xl font-bold text-left text-gray-700">
                        34,500
                    </p>
                    <div class="flex items-center text-sm text-green-500">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 1792 1792"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1408 1216q0 26-19 45t-45 19h-896q-26 0-45-19t-19-45 19-45l448-448q19-19 45-19t45 19l448 448q19 19 19 45z">
                            </path>
                        </svg>
                        <span>
                            5.5%
                        </span>
                        <span class="text-gray-400">
                            vs last month
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white shadow-lg rounded-2xl">
                <div class="flex items-center">
                    <span class="relative p-4 bg-orange-200 rounded-xl">
                        <svg width="40" fill="currentColor" height="40"
                            class="absolute h-4 text-orange-500 transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1362 1185q0 153-99.5 263.5t-258.5 136.5v175q0 14-9 23t-23 9h-135q-13 0-22.5-9.5t-9.5-22.5v-175q-66-9-127.5-31t-101.5-44.5-74-48-46.5-37.5-17.5-18q-17-21-2-41l103-135q7-10 23-12 15-2 24 9l2 2q113 99 243 125 37 8 74 8 81 0 142.5-43t61.5-122q0-28-15-53t-33.5-42-58.5-37.5-66-32-80-32.5q-39-16-61.5-25t-61.5-26.5-62.5-31-56.5-35.5-53.5-42.5-43.5-49-35.5-58-21-66.5-8.5-78q0-138 98-242t255-134v-180q0-13 9.5-22.5t22.5-9.5h135q14 0 23 9t9 23v176q57 6 110.5 23t87 33.5 63.5 37.5 39 29 15 14q17 18 5 38l-81 146q-8 15-23 16-14 3-27-7-3-3-14.5-12t-39-26.5-58.5-32-74.5-26-85.5-11.5q-95 0-155 43t-60 111q0 26 8.5 48t29.5 41.5 39.5 33 56 31 60.5 27 70 27.5q53 20 81 31.5t76 35 75.5 42.5 62 50 53 63.5 31.5 76.5 13 94z">
                            </path>
                        </svg>
                    </span>
                    <p class="ml-2 text-black text-md">
                        Qualidade
                    </p>
                </div>
                <div class="flex flex-col justify-start">
                    <p class="my-4 text-4xl font-bold text-left text-gray-700">
                        34,500
                    </p>
                </div>
            </div>
        </div> -->

        <div class="mt-10 mx-10">
            <v-card flat>
                <v-card-title class="d-flex align-center pe-2">
                    Anúncios
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" density="compact" label="Pesquisa" prepend-inner-icon="fa-solid fa-search"
                        variant="solo-filled" flat hide-details single-line></v-text-field>
                </v-card-title>

                <v-divider></v-divider>
                <v-data-table :items="filteredAdvertises" :headers="headers">
                    <template v-slot:item.title="{ item }">
                        <div class="flex items-center ms-5">
                            <div class="flex-shrink-0">
                                <a href="#" class="relative block">
                                    <img alt="profil" :src="item.thumbnail"
                                        class="mx-auto object-cover rounded-full h-10 w-10 border-gray-300 border-2" />
                                </a>
                            </div>
                            <div class="flex-1 ml-3">
                                <div class="font-medium ">
                                    {{ item.title }}
                                </div>
                                <div class="text-sm text-gray-600 ">
                                    {{ item.external_sku }}
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-slot:item.status="{ item }">
                        <v-chip
                            :color="item.status === 'Active' ? 'green' : (item.status === 'Incomplete' ? 'orange' : 'red')"
                            :text="item.status" class="text-uppercase" size="small" label></v-chip>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <Link :href="route('advertise.show', item.id)" class=" mr-5">
                            <i class="fa-solid fa-chevron-right"></i>
                        </Link>
                    </template>
                </v-data-table>
            </v-card>
        </div>

    </AuthenticatedLayout>
</template>
