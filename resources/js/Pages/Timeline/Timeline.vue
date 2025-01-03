<template>
    <div>
        <!-- ツイート投稿フォーム -->
        <form @submit.prevent="postTweet">
            <textarea
                v-model="newTweet"
                placeholder="What's happening?"
                maxlength="280"
                class="w-full h-24 p-2 border rounded"
                :disabled="loading"
            ></textarea
            ><!-- ツイート投稿中はフォームを無効化 -->
            <button
                type="submit"
                class="mt-2 px-4 py-2 bg-blue-500 text-white rounded"
                :disabled="loading"
            >
                <!-- ツイート投稿中はボタンを無効化 -->

                {{ loading ? "Posting..." : "Tweet" }}
                <!-- 状態に応じたボタンの表示 -->
            </button>
        </form>
        <div class="max-w-3xl mx-auto mt-8 px-4">
            <!-- タイムライン -->
            <div class="my-3 bg-white shadow-md rounded-lg overflow-hidden" v-for="tweet in tweets"
            :key="tweet.id">
                <!-- ヘッダー -->
                <div class="border-b p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img
                            class="w-10 h-10 rounded-full"
                            src="https://i.pravatar.cc/150?img=3"
                            alt="User profile"
                        />
                        <div>
                            <div class="font-bold">{{ tweet.user.name }}</div>
                            <div class="text-gray-500 text-sm">@{{ tweet.user.username }}</div>
                        </div>
                    </div>
                    <div class="text-gray-500">{{
                        new Date(tweet.created_at).toLocaleString()
                    }}</div>
                </div>

                <!-- ツイート内容 -->
                <div class="p-4">
                    <p class="text-lg text-gray-800">
                        {{ tweet.content }}
                    </p>
                </div>

                <!-- アクションボタン -->
                <div class="flex justify-between p-4 border-t">
                    <button
                        class="flex items-center space-x-2 text-gray-500 hover:text-blue-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 12h14M12 5l7 7-7 7"
                            ></path>
                        </svg>
                        <span>リツイート</span>
                    </button>
                    <button
                        class="flex items-center space-x-2 text-gray-500 hover:text-blue-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 9V5a3 3 0 016 0v4"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 15h16M12 9h0"
                            ></path>
                        </svg>
                        <span>返信</span>
                    </button>
                    <button
                        class="flex items-center space-x-2 text-gray-500 hover:text-blue-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M14 2L9 7m0 0l5 5m-5-5v12"
                            ></path>
                        </svg>
                        <span>いいね</span>
                    </button>
                    <button
                        class="flex items-center space-x-2 text-gray-500 hover:text-blue-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 8h10M7 12h10M7 16h10"
                            ></path>
                        </svg>
                        <span>共有</span>
                    </button>
                    <button
                        class="text-red-500 hover:text-red-700"
                        id="delete-button"
                        v-if="tweet.user.id === auth.user.id"
                    @click="deleteTweet(tweet.id)"
                    >
                        削除
                    </button>
                </div>
            </div>
        </div>

        <!-- エラーメッセージ表示 -->
        <p v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</p>

        <!-- ロード中スピナー -->
        <p v-if="loadingTweets" class="text-center mt-4">Loading tweets...</p>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

export default {
    setup() {
        // Inertiaのプロパティを取得
        const { props } = usePage();

        // ログイン中のユーザー情報を取得
        const auth = props.auth;

        // ツイート一覧を保持するリスト
        const tweets = ref([]);

        // 新しいツイートの内容を格納する状態
        const newTweet = ref("");

        // ツイート投稿中の状態を管理
        const loading = ref(false);

        // ツイート取得中の状態を管理
        const loadingTweets = ref(false);

        // エラーメッセージを格納する状態
        const errorMessage = ref("");

        /**
         * サーバーからツイート一覧を取得
         * 初回ロードやリロード時に使用される
         */
        const fetchTweets = async () => {
            //loadingTweets.value = true; // ツイート取得中の状態を設定
            try {
                const response = await axios.get(route("tweets.index")); // サーバーからツイートを取得
                tweets.value = response.data; // ツイート一覧を状態に保存
            } catch (error) {
                console.error("Error fetching tweets:", error); // デバッグ用にエラーをコンソールに表示
            } finally {
                loadingTweets.value = false; // ツイート取得が完了したら状態をリセット
            }
        };

        /**
         * 新しいツイートを投稿
         * フォーム送信時に実行される
         */
        const postTweet = async () => {
            loading.value = true; // ツイート投稿中の状態を設定
            try {
                const response = await axios.post(route("tweets.store"), {
                    content: newTweet.value, // フォームから新しいツイートの内容を送信
                });
                tweets.value.unshift(response.data); // 新しいツイートを一覧の先頭に追加
                newTweet.value = ""; // フォームの内容をクリア
            } catch (error) {
                console.error("Error posting tweet:", error); // デバッグ用にエラーをコンソールに表示
                errorMessage.value = "Failed to post tweet. Please try again.";
            } finally {
                loading.value = false; // ツイート投稿が完了したら状態をリセット
            }
        };

        // ツイートを削除
        const deleteTweet = async (tweetId) => {
            try {
                await axios.delete(route("tweets.destroy", { tweet: tweetId }));
                // ローカルのツイート一覧から削除
                tweets.value = tweets.value.filter(
                    (tweet) => tweet.id !== tweetId
                );
            } catch (error) {
                console.error("Error deleting tweet:", error);
                errorMessage.value =
                    "Failed to delete tweet. Please try again.";
            }
        };

        // コンポーネントがロードされたときにツイート一覧を取得
        fetchTweets();

        // Vueコンポーネントで使用する状態やメソッドを返す
        return {
            tweets,
            newTweet,
            postTweet,
            deleteTweet,
            loading,
            loadingTweets,
            errorMessage,
            auth,
        };
    },
};
</script>

<style scoped>
textarea {
    resize: none; /* テキストエリアのリサイズを無効化 */
}
</style>
