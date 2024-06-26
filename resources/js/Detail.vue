<template>
    <div class="lg:container lg:mx-auto lg:flex lg:gap-x-4">
        <div class="lg:w-96 lg:shrink-0">
            <section
                class="flex flex-col items-center justify-center overflow-hidden relative lg:sticky lg:top-0 lg:left-16 lg:p-4 mt-6 lg:mt-2.5"
            >
                <div class="flex relative items-center w-[175%] gap-2">
                    <DetailTransition :right-origin="state.direction === 'right'" zoom="in">
                        <div class="w-1/2 h-72" :key="state.index">
                            <img
                                class="w-full h-full object-bottom object-contain lg:invisible animate-float2"
                                :src="state.imageSrcPrev"
                                :srcset="`${state.imageSrcsetPrev}`"
                            />
                        </div>
                    </DetailTransition>
                    <DetailTransition :right-origin="state.direction === 'right'" zoom="out">
                        <div class="w-2/3 h-80" :key="state.index">
                            <img
                                class="w-full h-full object-bottom object-contain animate-float1"
                                :src="state.imageSrc"
                                :srcset="`${state.imageSrcset}`"
                            />
                        </div>
                    </DetailTransition>
                    <DetailTransition :right-origin="state.direction === 'right'" zoom="in">
                        <div class="w-1/2 h-72" :key="state.index">
                            <img
                                class="w-full h-full object-bottom object-contain lg:invisible animate-float3"
                                :src="state.imageSrcNext"
                                :srcset="`${state.imageSrcsetNext}`"
                            />
                        </div>
                    </DetailTransition>
                </div>
                <router-link
                    :to="{ name: 'tree', params: { id: state.indexPrev } }"
                    class="group bg-blue-lighter bg-opacity-80 absolute left-4 bottom-1/2 w-8 h-8 flex items-center justify-center blur-[0.5px] rounded-full"
                >
                    <ArrowLeftIcon class="w-6 h-6" />
                </router-link>
                <router-link
                    :to="{ name: 'tree', params: { id: state.indexNext } }"
                    class="group bg-blue-lighter bg-opacity-80 absolute right-4 bottom-1/2 w-8 h-8 flex items-center justify-center blur-[0.5px] rounded-full"
                >
                    <ArrowRightIcon class="w-6 h-6" />
                </router-link>
                <FadeInOutTransition>
                    <div :key="state.index" class="w-full text-center py-3">
                        <h2 class="font-display text-3xl pt-4 text-center">{{ trees[state.index - 1][lang].title }}</h2>
                        <span class="text-sm pb"
                            >{{ trees[state.index - 1][lang].location
                            }}{{ trees[state.index - 1][lang].protest && `/${trees[state.index - 1][lang].protest}`
                            }}{{
                                trees[state.index - 1][lang].dating && `/${trees[state.index - 1][lang].dating}`
                            }}</span
                        >
                        <div class="hidden pt-10 left-0 right-0 lg:block w-full">
                            <MusicPlayer
                                class="container mx-auto"
                                :index="state.index"
                                :key="state.index"
                            ></MusicPlayer>
                        </div>
                    </div>
                </FadeInOutTransition>
            </section>
        </div>
        <FadeInOutTransition>
            <div class="lg:relative lg:w-full lg:mt-72" :key="state.index">
                <section class="container mx-auto px-5 mt-6 lg:mr-0">
                    <h3 class="text-xl opacity-60 pb-2">{{ $t("Story") }}</h3>
                    <p class="pb-8 lg:text-2xl whitespace-pre-wrap" v-html="trees[state.index - 1][lang].story"></p>
                    <h3 class="text-xl opacity-60 pb-2">{{ $t("Poem") }}</h3>
                    <p class="lg:text-xl whitespace-pre-wrap">{{ trees[state.index - 1].poem }}</p>
                    <p class="text-sm pt-3 lg:text-xl pb-3" v-html="trees[state.index - 1].references"></p>
                    <p class="pt-3">{{ $t("Performance and singing") }}: {{ trees[state.index - 1].performers }}</p>
                    <p>Mastering: Peter Groll</p>
                </section>
                <div
                    class="sticky container mx-auto px-5 bottom-7 pb-2.5 pt-10 left-0 right-0 lg:hidden"
                    :key="state.index"
                >
                    <MusicPlayer :index="state.index"></MusicPlayer>
                </div>
                <section class="flex flex-col container mx-auto px-5 gap-2.5 lg:mr-0 lg:flex-row lg:py-20">
                    <router-link
                        to="/trees"
                        class="font-display py-3 px-8 text-lg border-white/30 border-2 hover:bg-orange hover:border-orange hover:text-blue-darker transition rounded-[70px] text-center"
                    >
                        {{ $t("Discover visitors stories") }}
                    </router-link>
                    <a
                        href="https://jira.sng.sk/plugins/servlet/loginfreeRedirMain?portalid=9"
                        class="font-display py-3 px-8 text-lg border-white/30 border-2 hover:bg-orange hover:border-orange hover:text-blue-darker transition rounded-[70px] text-center"
                        target="_blank"
                    >
                        {{ $t("Submit your story") }}
                    </a>
                </section>
            </div>
        </FadeInOutTransition>
    </div>
</template>
<script setup>
import MusicPlayer from "./MusicPlayer.vue";
import { trees } from "./consts";
import { useRoute } from "vue-router";
import { watch, reactive, ref } from "vue";
import { useLanguage } from "./composables/language";
import DetailTransition from "./components/DetailTransition.vue";
import FadeInOutTransition from "./components/FadeInOutTransition.vue";
import ArrowLeftIcon from "./icons/ArrowLeftIcon.vue";
import ArrowRightIcon from "./icons/ArrowRightIcon.vue";

const route = useRoute();
const state = reactive({
    index: 0,
    indexNext: 0,
    indexPrev: 0,
    imageUrl: "",
    imageUrlNext: "",
    imageUrlPrev: "",
    direction: null,
});
const oldRouteIndex = ref();
const { lang } = useLanguage();

watch(
    route,
    () => {
        const newIndex = parseInt(route.params.id) || 1;
        state.index = newIndex;
        state.indexNext = !parseInt(route.params.id) || state.index > trees.length - 1 ? 1 : state.index + 1;
        state.indexPrev = !parseInt(route.params.id) || state.index < 2 ? trees.length : state.index - 1;

        state.imageSrc = new URL(`./assets/trees/tree-detail-${state.index}.png`, import.meta.url).href;
        state.imageSrcset =
            new URL(`./assets/trees/tree-detail-${state.index}.png`, import.meta.url).href +
            " 1x, " +
            new URL(`./assets/trees/tree-detail-${state.index}@2x.png`, import.meta.url).href +
            " 2x";
        state.imageSrcNext = new URL(`./assets/trees/tree-detail-${state.indexNext}.png`, import.meta.url).href;
        state.imageSrcsetNext =
            new URL(`./assets/trees/tree-detail-${state.indexNext}.png`, import.meta.url).href +
            " 1x, " +
            new URL(`./assets/trees/tree-detail-${state.indexNext}@2x.png`, import.meta.url).href +
            " 2x";
        state.imageSrcPrev = new URL(`./assets/trees/tree-detail-${state.indexPrev}.png`, import.meta.url).href;
        state.imageSrcsetPrev =
            new URL(`./assets/trees/tree-detail-${state.indexPrev}.png`, import.meta.url).href +
            " 1x, " +
            new URL(`./assets/trees/tree-detail-${state.indexPrev}@2x.png`, import.meta.url).href +
            " 2x";

        if (!oldRouteIndex) return;
        if (newIndex === 1 && oldRouteIndex.value === trees.length) {
            state.direction = "right";
        } else if (newIndex === trees.length && oldRouteIndex.value === 1) {
            state.direction = "left";
        } else if (newIndex > oldRouteIndex.value) {
            state.direction = "right";
        } else {
            state.direction = "left";
        }

        oldRouteIndex.value = newIndex;
    },
    { immediate: true }
);
</script>
