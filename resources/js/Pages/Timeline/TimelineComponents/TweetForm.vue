<template>
    <form @submit.prevent="postTweet">
        <textarea
            v-model="newTweet"
            placeholder="What's happening?"
            maxlength="280"
            class="w-full h-24 p-2 border rounded"
            :disabled="loading"
        ></textarea>
        <button
            type="submit"
            class="mt-2 px-4 py-2 bg-blue-500 text-white rounded"
            :disabled="loading"
        >
            {{ loading ? "Posting..." : "Tweet" }}
        </button>
    </form>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
    emits: ["tweet-posted"], // 親コンポーネントにツイート投稿を通知するイベント
    setup(props, { emit }) {
        const newTweet = ref("");
        const loading = ref(false);

        const postTweet = async () => {
            loading.value = true;
            try {
                const response = await axios.post(route("tweets.store"), {
                    content: newTweet.value,
                });
                emit("tweet-posted", response.data); // 新しいツイートを親に通知
                newTweet.value = ""; // フォームをリセット
            } catch (error) {
                console.error("Error posting tweet:", error);
            } finally {
                loading.value = false;
            }
        };

        return {
            newTweet,
            postTweet,
            loading,
        };
    },
};
</script>

<style scoped>
textarea {
    resize: none;
}
</style>
