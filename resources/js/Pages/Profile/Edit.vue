<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl">
                <div class="max-w-4xl mx-auto">
                    <!-- プロフィールカード -->
                    <div class="bg-white shadow-md overflow-hidden">
                        <!-- カバー画像 -->
                        <div class="relative">
                            <img
                                class="w-full h-48 object-cover bg-black"
                                src="https://via.placeholder.com/1200x400"
                                alt="Cover Image"
                            />
                            <div
                                class="absolute bottom-[-30px] left-4"
                            >
                                <div class="flex items-center space-x-4">
                                    <!-- プロフィール画像 -->
                                    <img
                                        class="w-20 h-20 rounded-full border-4 border-white"
                                        src="https://i.pravatar.cc/150?img=3"
                                        alt="User Profile"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- ユーザー情報 -->
                        <div class="p-6 mt-10">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex flex-col">
                                    <div class="font-bold text-2xl">
                                        ユーザー名
                                    </div>
                                    <div class="text-sm">@username</div>
                                </div>
                                <!-- 編集ボタン -->
                                <div class="flex justify-start">
                                    <button
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                                    >
                                        プロフィール編集
                                    </button>
                                </div>
                            </div>
                            <!-- 自己紹介 -->
                            <div class="mb-3">
                                <p class="text-gray-700">
                                    ここにユーザーの自己紹介文が入ります。趣味や活動内容、考えなどを記載できます。
                                </p>
                            </div>

                            <!-- プロフィール詳細 -->
                            <div class="flex gap-x-3">
                                <div class="flex items-center space-x-2">
                                    <span class="font-semibold text-gray-800"
                                        >フォロワー数:</span
                                    >
                                    <span class="text-gray-600">1,234</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-semibold text-gray-800"
                                        >フォロー中:</span
                                    >
                                    <span class="text-gray-600">567</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
