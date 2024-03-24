<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const state = ref(Math.floor(Math.random() * 1000000000))

// recive accounts prop from controller
const props = defineProps(['accounts']);

const headers = [
    { title: 'Nome', key: 'name' },
    { title: 'Seller ID', key: 'seller_id' },
    {
        title: 'Data de vinculação',
        key: 'created_at',
        value: account => new Date(account.created_at).toLocaleString('pt-br')
    },
    { title: 'Ações', key: 'actions', align: 'end' }
]

const syncAccount = async (accountId) => {
    let res = await axios.get(`/accounts/sync/${accountId}`)

    Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    }).fire({
        icon: 'success',
        title: res.data.message
    })
}
</script>

<template>

    <Head title="Accounts" />

    <AuthenticatedLayout>

        <!-- add account button orange-600 -->
        <div class="flex justify-end">
            <a :href="`https://sellercentral.amazon.com.br/apps/authorize/consent?application_id=amzn1.sp.solution.dd1ba476-2cc9-4aae-ba79-629aabcf8f79&state=${state}&version=beta`"
                class="flex items-center justify-center px-4 py-2 mt-4 mr-15 text-sm font-medium text-white bg-orange-600 rounded-md hover:bg-orange-700">
                Adicionar Conta
            </a>
        </div>

        <div class="mt-5 container flex flex-col items-center justify-center w-full mx-auto bg-white rounded-lg shadow">
            <div class="w-full px-4 py-5 border-b sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 ">
                    Contas Amazon
                </h3>
                <p class="max-w-2xl mt-1 text-sm text-gray-500 ">
                    Suas contas vinculadas com o sistema
                </p>
            </div>
            <v-card flat class="w-full">
                <v-data-table :items="accounts" :headers="headers" :hide-default-footer="true">
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
                                    {{ item.sku }}
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-slot:item.status="{ item }">
                        <v-chip :color="item.status == 'ativo' ? 'green' : 'red'" :text="item.status"
                            class="text-uppercase" size="small" label></v-chip>
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <i class="fa-solid fa-arrows-rotate mr-3 cursor-pointer" title="Sincronizar" @click="syncAccount(item.id)"></i>
                        <i class="fa-solid fa-chevron-right cursor-pointer" title="Detalhes"></i>
                    </template>

                    <template #bottom></template>
                </v-data-table>
            </v-card>
        </div>


    </AuthenticatedLayout>
</template>
