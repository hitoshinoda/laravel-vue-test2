<template>
    <button
        @click="deleteTweet"
        :disabled="loading"
        class="text-red-500 hover:text-red-700 flex items-center space-x-2"
    >
        <span v-if="!loading">削除</span>
        <span v-else>削除中...</span>
    </button>
</template>

<script>
import axios from "axios";

export default {
    props: {
        tweetId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            loading: false,
        };
    },
    methods: {
        async deleteTweet() {
            this.loading = true;
            try {
                await axios.delete(route("tweets.destroy", { tweet: this.tweetId }));
                // 削除が成功したら親に通知
                this.$emit("delete-tweet", this.tweetId);
            } catch (error) {
                console.error("ツイート削除エラー:", error);
                alert("削除に失敗しました。もう一度お試しください。");
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
button:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}
</style>
