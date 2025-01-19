<template>
    <div>
        <!-- ツイート投稿フォーム -->
        <TweetForm @tweet-posted="addNewTweet" />

        <!-- ツイートリスト -->
        <TweetList
            :tweets="tweets"
            :auth="auth"
            @delete-tweet="deleteTweet"
        />

        <!-- エラーメッセージ表示 -->
        <p v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</p>
    </div>
</template>

<script>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import TweetForm from "./TimelineComponents/TweetForm.vue";
import TweetList from "./TimelineComponents/TweetList.vue";

export default {
    components: {
        TweetForm,
        TweetList,
    },
    setup() {
        const { props } = usePage();
        const auth = props.auth;

        const tweets = ref([]);
        const errorMessage = ref("");

        const fetchTweets = async () => {
            try {
                const response = await axios.get(route("tweets.index"));
                tweets.value = response.data;
            } catch (error) {
                console.error("Error fetching tweets:", error);
            }
        };

        const addNewTweet = (tweet) => {
            tweets.value.unshift(tweet);
        };

        const deleteTweet = async (tweetId) => {
            try {
                await axios.delete(route("tweets.destroy", { tweet: tweetId }));
                tweets.value = tweets.value.filter(
                    (tweet) => tweet.id !== tweetId
                );
            } catch (error) {
                console.error("Error deleting tweet:", error);
                errorMessage.value = "Failed to delete tweet.";
            }
        };

        fetchTweets();

        return {
            tweets,
            addNewTweet,
            deleteTweet,
            auth,
            errorMessage,
        };
    },
};
</script>
