<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import MentorLayout from '@/Layouts/MentorLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps(['users']);

const headers = [
    { title: 'Nome', key: 'name' },
    { title: 'Email', key: 'email' },
    { title: 'Data', key: 'created_at', value: item => new Date(item.created_at).toLocaleString("pt-br") },
    { title: 'Ações', key: 'actions' },
]

</script>

<template>

    <Head title="Mentorados" />
    <MentorLayout>

        <div class="flex justify-end items-center mt-5 mx-10">
            <Link :href="route('users.create')">
            <PrimaryButton>
                <i class="fas fa-plus mr-2"></i> Cadastrar Mentorado
            </PrimaryButton>
            </Link>
        </div>

        <div class="mt-5 mx-10 ">
            <v-card flat>
                <v-card-title>
                    Mentorados
                    <v-spacer></v-spacer>
                </v-card-title>
                <v-divider></v-divider>
                <v-data-table :items="props.users" :headers="headers"></v-data-table>
            </v-card>
        </div>
    </MentorLayout>
</template>