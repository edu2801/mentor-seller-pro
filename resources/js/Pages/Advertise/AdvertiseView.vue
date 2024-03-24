<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps(['advertise']);

const advertise = ref(props.advertise);

const headers = [
    { title: 'Tópico', key: 'type' },
    { title: 'Nota', key: 'grade', value: item => item.grade.toLocaleString('pt-br', { style: 'percent', minimumFractionDigits: 1, maximumFractionDigits: 1 }) },
    { title: 'Descrição', key: 'message', value: item => !item.message ? 'Excelente' : item.message },
    { title: 'Data', key: 'created_at', value: item => new Date(item.created_at).toLocaleString("pt-br") },
]

const parsedBulletPoints = ref(JSON.parse(advertise.value.bullet_points));

watch(() => advertise.bullet_points, (newValue) => {
    parsedBulletPoints.value = JSON.parse(newValue);
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex justify-end items-center mt-5 mx-10">
            <Link :href="route('advertise.sync', advertise.id, )">
                <PrimaryButton>
                    <i class="fas fa-sync-alt mr-2"></i> Sincronizar
                </PrimaryButton>
            </Link>
        </div>

        <div class="flex justify-center items-center mt-5 mx-10">
            <div class="p-4 bg-white shadow-lg rounded-2xl">
                <div class="flex items
                -center">
                    <span class="relative rounded-xl ">
                        <img :src="advertise.thumbnail" alt="Advertise Image"
                            class="min-h-40 min-w-40 rounded-xl p-4 bg-orange-500">
                        <h1 class="text-lg font-bold text-gray-800">Images</h1>
                        <div class="flex items
                    -center mt-2">
                            <template v-for="image in advertise.images">
                                <span class="relative p-2 bg-orange-500 rounded-xl mr-3">
                                    <img :src="image.url" alt="Advertise Image" class="h-20 w-20 rounded-xl">
                                </span>
                            </template>
                        </div>
                    </span>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-800">{{ advertise.title }}</h1>
                        <p v-html="advertise.description" class="text-sm text-gray-600 mt-5"></p>

                        <!-- Show advertise.bullet_points. Need to encode and each -->
                        <div class="mt-5">
                            <h1 class="text-lg font-bold text-gray-800">Bullet Points</h1>
                            <ul class="list-inside list-none">
                                <template v-for="bullet in parsedBulletPoints">
                                    <li>{{ bullet.value }}</li>
                                </template>
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="mt-4">

                </div>
            </div>
        </div>

        <!-- Show each grade.value, grade.description, grade.created_at in advertise.grade  -->
        <div class="mt-10 mx-10 ">
            <v-card flat>
                <v-card-title>
                    Notas
                    <v-spacer></v-spacer>
                </v-card-title>
                <v-divider></v-divider>
                <v-data-table :items="advertise.grades" :headers="headers"></v-data-table>
            </v-card>
        </div>

        <div class="mt-10 mx-10">.</div>

    </AuthenticatedLayout>

</template>