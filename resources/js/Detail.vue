<template>
    <div class="lg:container lg:mx-auto lg:flex lg:gap-x-4">
        <div class="lg:w-96 lg:shrink-0">
            <section
                class="flex flex-col items-center justify-center overflow-hidden relative mt-16 lg:sticky lg:top-0 lg:left-16"
            >
                <div class="flex relative items-center w-[175%] gap-2">
                    <img class="w-1/2 max-h-96 py-3 object-contain lg:invisible" :src="state.imageUrlPrev" />
                    <img class="w-2/3 max-h-96 object-contain" :src="state.imageUrl" />
                    <img class="w-1/2 max-h-96 py-3 object-contain lg:invisible" :src="state.imageUrlNext" />
                </div>
                <router-link
                    :to="{ name: 'tree', params: { id: state.indexPrev } }"
                    class="group bg-blue-lighter bg-opacity-80 absolute left-4 bottom-1/2 w-8 h-8 flex items-center justify-center blur-[0.5px] rounded-full"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-blue-darkest" viewBox="0 0 256 256">
                        <path
                            stroke-width="16"
                            d="M220,128a4,4,0,0,1-4,4H49.66l65.17,65.17a4,4,0,0,1-5.66,5.66l-72-72a4,4,0,0,1,0-5.66l72-72a4,4,0,0,1,5.66,5.66L49.66,124H216A4,4,0,0,1,220,128Z"
                        ></path>
                    </svg>
                </router-link>
                <router-link
                    :to="{ name: 'tree', params: { id: state.indexNext } }"
                    class="group bg-blue-lighter bg-opacity-80 absolute right-4 bottom-1/2 w-8 h-8 flex items-center justify-center blur-[0.5px] rounded-full"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-blue-darkest" viewBox="0 0 256 256">
                        <path
                            stroke-width="16"
                            d="M218.83,130.83l-72,72a4,4,0,0,1-5.66-5.66L206.34,132H40a4,4,0,0,1,0-8H206.34L141.17,58.83a4,4,0,0,1,5.66-5.66l72,72A4,4,0,0,1,218.83,130.83Z"
                        ></path>
                    </svg>
                </router-link>
                <h2 class="font-display text-3xl pt-4">{{ trees[state.index].title }}</h2>
                <span class="text-sm pb"
                    >{{ trees[state.index].location }}/{{ trees[state.index].protest }}/{{
                        trees[state.index].dating
                    }}</span
                >
                <div class="hidden pt-10 left-0 right-0 lg:block w-full">
                    <MusicPlayer class="container mx-auto" :index="state.index"></MusicPlayer>
                </div>
            </section>
        </div>
        <div class="lg:relative lg:w-full lg:mt-96">
            <section class="container mx-auto px-5 mt-6 lg:mr-0">
                <h3 class="text-xl opacity-60 pb-2">{{ $t("Story") }}</h3>
                <p class="pb-8 lg:text-2xl" v-html="trees[state.index].story"></p>
                <h3 class="text-xl opacity-60 pb-2">{{ $t("Poem") }}</h3>
                <p class="lg:text-xl" v-html="trees[state.index].poem"></p>
                <p class="text-sm pt-3 lg:text-xl" v-html="trees[state.index].references"></p>
            </section>
            <div class="sticky container mx-auto px-5 bottom-0 pb-2.5 pt-10 left-0 right-0 lg:hidden">
                <MusicPlayer :index="state.index"></MusicPlayer>
            </div>
            <section class="flex flex-col container mx-auto px-5 gap-2.5 lg:mr-0 lg:flex-row lg:py-20">
                <!-- TODO: Add proper links -->
                <router-link
                    to="/about"
                    class="font-display py-3 px-8 text-lg border-white/30 border-2 hover:bg-orange hover:border-orange hover:text-blue-darker transition rounded-[70px] text-center"
                >
                    {{ $t("Discover visitors stories") }}
                </router-link>
                <router-link
                    to="/about"
                    class="font-display py-3 px-8 text-lg border-white/30 border-2 hover:bg-orange hover:border-orange hover:text-blue-darker transition rounded-[70px] text-center"
                >
                    {{ $t("Submit your story") }}
                </router-link>
            </section>
        </div>
    </div>
</template>
<script setup>
import MusicPlayer from "./MusicPlayer.vue";
import { trees } from "./consts";
import { useRoute } from "vue-router";
import { watch, reactive } from "vue";

const route = useRoute();
const state = reactive({ index: 0, indexNext: 0, indexPrev: 0, imageUrl: "", imageUrlNext: "", imageUrlPrev: "" });

watch(
    route,
    () => {
        state.index = parseInt(route.params.id) || 0;
        state.indexNext = !parseInt(route.params.id) || state.index > trees.length - 2 ? 1 : state.index + 1;
        state.indexPrev = !parseInt(route.params.id) || state.index < 1 ? trees.length - 1 : state.index - 1;

        state.imageUrl = new URL(`./assets/trees/${state.index}.png`, import.meta.url).href;
        state.imageUrlNext = new URL(`./assets/trees/${state.indexNext}.png`, import.meta.url).href;
        state.imageUrlPrev = new URL(`./assets/trees/${state.indexPrev}.png`, import.meta.url).href;
    },
    { immediate: true }
);
</script>
