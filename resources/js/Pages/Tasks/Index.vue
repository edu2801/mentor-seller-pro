<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { VCard } from 'vuetify/lib/components/index.mjs';

const props = defineProps({
    tasks: {
        type: Array,
        required: true,
    },
});

const tasks = ref(props.tasks);
const showModal = ref(false);
const taskForm = ref({
    name: '',
    description: '',
});

const deleteTask = (taskId) => {
    tasks.value = tasks.value.filter((task) => task.id !== taskId);
};

const completeTask = (taskId) => {
    const task = tasks.value.find((task) => task.id === taskId);
    task.completed = !task.completed;
};

const getTaskStatus = (task) => {
    return task.completed ? 'Concluída' : 'Pendente';
};

const getTaskStatusClass = (task) =>
    task.completed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

const createTask = () => {
    const route = isMentor ? `/mentor/${userId}/tasks` : '/tasks'
        router.post(route, taskForm.value);


    showModal.value = false;
};

const isMentor = route().current().includes('mentor.user.');
const userId = route().params.user ?? null;
</script>

<template>

    <Head title="Tarefas" />

    <AuthenticatedLayout>
        <!-- Button to create task orange-600 -->
        <div class="flex justify-end mt-5 mr-15">
            <!-- if have advertise in query params create a button to remove filters -->
            <Link v-if="route()?.params?.advertise" :href="!isMentor ? route('tasks') : route('mentor.user.tasks', userId)"
                class="px-4 py-2 text-sm font-medium text-orange-600 rounded-md hover:text-orange-800 mr-5">
            Remover Filtros
            </Link>
            <p @click="showModal = true"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-md hover:bg-orange-700">
                Criar Tarefa
            </p>
        </div>

        <!-- List of tasks with a button to complete and on clicl open modal -->
        <VCard class="mt-5 mx-15">
            <ul class="divide-y divide-gray-200">
                <li v-for="task in tasks" :key="task.id" class="py-4 px-2 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ task.name }}</h3>
                        <p class="text-sm text-gray-500">{{ task.description }}</p>

                        <!-- create a element with name "Referente ao anúncio: hiperlink{{task?.advertise.titke}} -->

                        <Link v-if="task?.advertise?.id" :href="!isMentor ? route('advertise.show', task.advertise.id) : route('mentor.user.advertise.show', {amazonAdvertise: task.advertise.id, user: userId})"
                            class="text-sm text-gray-500 hover:text-gray-700">Referente ao anúncio:
                        {{ task.advertise.title }}</Link>
                    </div>
                    <div class="min-w-48">
                        <span :class="getTaskStatusClass(task)"
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                            {{ getTaskStatus(task) }}
                        </span>
                        <button @click="completeTask(task.id)" class="ml-4 px-4 py-2 text-green-600 rounded">
                            <i class="fas fa-check"></i>
                        </button>
                        <button @click="deleteTask(task.id)" class="ml-2 px-4 py-2 text-red-500 rounded">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </li>
            </ul>
        </VCard>


        <Modal :show="showModal" @close="showModal = false">
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <h3 class="text-lg font-medium leading-6">Criar Tarefa</h3>
            </div>

            <form class="p-5" @submit.prevent="createTask">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Título</label>
                    <input v-model="taskForm.name" type="text" id="name" name="Nome"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                    <textarea v-model="taskForm.description" id="description" name="Descrição" rows="3"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showModal = false"
                        class="px-4 py-2 bg-gray-500 text-white rounded">Cancelar</button>
                    <button type="submit" class="ml-4 px-4 py-2 bg-orange-600 text-white rounded">Salvar</button>
                </div>
            </form>
        </Modal>
    </AuthenticatedLayout>
</template>
