<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Accounts" />

    <AuthenticatedLayout>

        <div
            class="mt-5 container flex flex-col items-center justify-center w-full mx-auto bg-white rounded-lg shadow">
            <div class="w-full px-4 py-5 border-b sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 ">
                    User database
                </h3>
                <p class="max-w-2xl mt-1 text-sm text-gray-500 ">
                    Details and informations about user.
                </p>
            </div>
            <ul class="flex flex-col divide-y divide">
                <li class="flex flex-row">
                    <div class="flex items-center flex-1 p-4 cursor-pointer select-none">
                        <div class="flex flex-col items-center justify-center w-10 h-10 mr-4">
                            <a href="#" class="relative block">
                                <img alt="profil" src="/images/person/6.jpg"
                                    class="mx-auto object-cover rounded-full h-10 w-10 " />
                            </a>
                        </div>
                        <div class="flex-1 pl-1 mr-16">
                            <div class="font-medium ">
                                Jean Marc
                            </div>
                            <div class="text-sm text-gray-600 ">
                                Developer
                            </div>
                        </div>
                        <div class="text-xs text-gray-600 ">
                            6:00 AM
                        </div>
                        <button class="flex justify-end w-24 text-right">
                            <svg width="20" fill="currentColor" height="20"
                                class="text-gray-500 hover:text-gray-800  "
                                viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1363 877l-742 742q-19 19-45 19t-45-19l-166-166q-19-19-19-45t19-45l531-531-531-531q-19-19-19-45t19-45l166-166q19-19 45-19t45 19l742 742q19 19 19 45t-19 45z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </li>
                <li class="flex flex-row">
                    <div class="flex items-center flex-1 p-4 cursor-pointer select-none">
                        <div class="flex flex-col items-center justify-center w-10 h-10 mr-4">
                            <a href="#" class="relative block">
                                <img alt="profil" src="/images/person/10.jpg"
                                    class="mx-auto object-cover rounded-full h-10 w-10 " />
                            </a>
                        </div>
                        <div class="flex-1 pl-1 mr-16">
                            <div class="font-medium ">
                                Designer
                            </div>
                            <div class="text-sm text-gray-600 ">
                                Charlie Moi
                            </div>
                        </div>
                        <div class="text-xs text-gray-600 ">
                            6:00 AM
                        </div>
                        <button class="flex justify-end w-24 text-right">
                            <svg width="20" fill="currentColor" height="20"
                                class="text-gray-500 hover:text-gray-800  "
                                viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1363 877l-742 742q-19 19-45 19t-45-19l-166-166q-19-19-19-45t19-45l531-531-531-531q-19-19-19-45t19-45l166-166q19-19 45-19t45 19l742 742q19 19 19 45t-19 45z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </li>
                <li class="flex flex-row">
                    <div class="flex items-center flex-1 p-4 cursor-pointer select-none">
                        <div class="flex flex-col items-center justify-center w-10 h-10 mr-4">
                            <a href="#" class="relative block">
                                <img alt="profil" src="/images/person/3.jpg"
                                    class="mx-auto object-cover rounded-full h-10 w-10 " />
                            </a>
                        </div>
                        <div class="flex-1 pl-1 mr-16">
                            <div class="font-medium ">
                                CEO
                            </div>
                            <div class="text-sm text-gray-600 ">
                                Marine Jeanne
                            </div>
                        </div>
                        <div class="text-xs text-gray-600 ">
                            6:00 AM
                        </div>
                        <button class="flex justify-end w-24 text-right">
                            <svg width="20" fill="currentColor" height="20"
                                class="text-gray-500 hover:text-gray-800  "
                                viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1363 877l-742 742q-19 19-45 19t-45-19l-166-166q-19-19-19-45t19-45l531-531-531-531q-19-19-19-45t19-45l166-166q19-19 45-19t45 19l742 742q19 19 19 45t-19 45z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </li>
                <li class="flex flex-row">
                    <div class="flex items-center flex-1 p-4 cursor-pointer select-none">
                        <div class="flex flex-col items-center justify-center w-10 h-10 mr-4">
                            <a href="#" class="relative block">
                                <img alt="profil" src="/images/person/7.jpg"
                                    class="mx-auto object-cover rounded-full h-10 w-10 " />
                            </a>
                        </div>
                        <div class="flex-1 pl-1 mr-16">
                            <div class="font-medium ">
                                CTO
                            </div>
                            <div class="text-sm text-gray-600 ">
                                Boby PArk
                            </div>
                        </div>
                        <div class="text-xs text-gray-600 ">
                            6:00 AM
                        </div>
                        <button class="flex justify-end w-24 text-right">
                            <svg width="20" fill="currentColor" height="20"
                                class="text-gray-500 hover:text-gray-800  "
                                viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1363 877l-742 742q-19 19-45 19t-45-19l-166-166q-19-19-19-45t19-45l531-531-531-531q-19-19-19-45t19-45l166-166q19-19 45-19t45 19l742 742q19 19 19 45t-19 45z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </li>
            </ul>
        </div>


    </AuthenticatedLayout>
</template>
