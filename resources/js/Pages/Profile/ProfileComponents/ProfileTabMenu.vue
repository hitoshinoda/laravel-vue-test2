<template>
    <div>
        <!-- タブメニュー -->
        <div class="tab-menu" ref="tabMenu">
            <button
                v-for="tab in tabs"
                :key="tab"
                :class="{ active: currentTab === tab }"
                @click="selectTab(tab)"
            >
                {{ tabNames[tab] }}
            </button>
            <div class="tab-indicator" :style="indicatorStyle"></div>
        </div>

        <!-- タブコンテンツ -->
        <transition name="fade">
            <div class="tab-content">
                <div v-if="loading" class="text-center py-4">Loading...</div>
                <div v-else-if="tweets.length === 0" class="text-center py-4">No tweets found.</div>
                <div v-else>
                    <TweetList v-if="currentTab === 'tweets'" :tweets="tweets" :auth="auth" />
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import TweetList from "@/Pages/Timeline/TimelineComponents/TweetList.vue";

export default {
    data() {
        return {
            tabs: ["tweets", "replies", "likes"],
            tabNames: {
                tweets: "Tweets",
                replies: "Replies",
                likes: "Likes",
            },
            currentTab: "tweets",
            tweets: [], // ツイートリストを保持
            loading: false, // ローディング状態
            tabWidth: 0, // 各タブの幅
        };
    },
    components: {
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
            deleteTweet,
            auth,
            errorMessage,
        };
    },
    computed: {
        indicatorStyle() {
            const index = this.tabs.indexOf(this.currentTab);
            return {
                width: `${this.tabWidth}px`,
                transform: `translateX(${index * this.tabWidth}px)`,
                transition: "transform 0.3s ease, width 0.3s ease",
            };
        },
    },
    methods: {
        async selectTab(tab) {
            this.currentTab = tab;
            this.loading = true;
            this.tweets = [];
            try {
                const response = await axios.get(route(`tabs.${tab}`));
                this.tweets = response.data.tweets; // APIから取得したツイートリストを保存
            } catch (error) {
                console.error("Error loading tab content:", error);
            } finally {
                this.loading = false;
            }
        },
        updateTabWidth() {
            const tabMenu = this.$refs.tabMenu;
            if (tabMenu) {
                this.tabWidth = tabMenu.offsetWidth / this.tabs.length;
            }
        },
    },
    mounted() {
        this.selectTab(this.currentTab);
        this.updateTabWidth();
        window.addEventListener("resize", this.updateTabWidth);
    },
    beforeUnmount() {
        window.removeEventListener("resize", this.updateTabWidth);
    },
};
</script>

<style scoped>
.tab-menu {
    display: flex;
    position: relative;
    border-bottom: 1px solid #e1e8ed;
    margin-bottom: 20px;
}

button {
    flex: 1;
    text-align: center;
    padding: 10px 0;
    background: none;
    border: none;
    font-size: 16px;
    color: #657786;
    font-weight: bold;
    cursor: pointer;
    position: relative;
    z-index: 1;
}

button.active {
    color: #1da1f2;
}

.tab-indicator {
    position: absolute;
    bottom: 0;
    height: 2px;
    background: #1da1f2;
    left: 0;
}

.tab-content {
    padding: 20px;
    font-size: 16px;
    color: #14171a;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
