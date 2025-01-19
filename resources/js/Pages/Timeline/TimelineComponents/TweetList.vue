<template>
    <div class="max-w-3xl mx-auto mt-8 px-4">
        <!-- タイムライン -->
        <div
            class="my-3 bg-white shadow-md rounded-lg overflow-hidden"
            v-for="tweet in tweets"
            :key="tweet.id"
        >
            <!-- ヘッダー -->
            <div class="border-b p-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img
                        class="w-10 h-10 rounded-full"
                        :src="tweet.user.profile_image_url"
                        alt="User profile"
                    />
                    <div>
                        <div class="font-bold">{{ tweet.user.name }}</div>
                        <div class="text-gray-500 text-sm">
                            @{{ tweet.user.username }}
                        </div>
                    </div>
                </div>
                <div class="text-gray-500">
                    {{ new Date(tweet.created_at).toLocaleString() }}
                </div>
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
                    <Heart
                        :tweetId="tweet.id"
                        :initialLiked="tweet.liked"
                        :initialLikesCount="tweet.likesCount"
                    />
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
                <TweetDeleteButton
                    v-if="tweet.user.id === auth.user.id"
                    :tweet-id="tweet.id"
                    @delete-tweet="$emit('delete-tweet', $event)"
                />
            </div>
        </div>
    </div>
</template>

<script>
import TweetDeleteButton from "./TweetDeleteButton.vue";
import Heart from "@/components/Heart.vue";

export default {
    components: { TweetDeleteButton, Heart },
    props: {
        tweets: {
            type: Array,
            required: true,
        },
        auth: {
            type: Object,
            required: true,
        },
    },
};
</script>

<style scoped></style>
