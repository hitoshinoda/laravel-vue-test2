<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.user;

const form = useForm({
    profile_image: null,
});

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const fileSelected = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.profile_image = file; // ファイルをフォームデータに設定
    }
};
</script>

<template>
    <section>
        <form
            @submit.prevent="form.post(route('profile.updateImage'))"
            class="mt-6 space-y-6"
            method="post"
            enctype="multipart/form-data"
        >
            <div>
                <InputLabel for="profile_image" value="Profile Image" />
                <input
                    id="profile_image"
                    type="file"
                    class="mt-1 block w-full"
                    @change="fileSelected"
                />

                <InputError class="mt-2" :message="form.errors.profile_image" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        {{ status }}
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
