<template>
    <div
        class="flex items-center justify-between px-3 py-3 bg-orange rounded-full space-x-2"
    >
        <div v-if="wasStarted" class="flex items-center space-x-1 w-full">
            <div class="text-sm text-gray-500 w-12 text-center">
                {{ formattedTime }}
            </div>
            <SliderRoot
                v-model="currentTime"
                class="relative flex items-center select-none touch-none w-full h-5"
                :max="duration"
                @pointerdown="seekUp"
                @pointerup="seekDown"
            >
                <SliderTrack
                    class="bg-[#c87933] relative grow rounded-full h-0.5"
                >
                    <SliderRange
                        class="absolute bg-blue-darker rounded-full h-0.5"
                    />
                </SliderTrack>
                <SliderThumb class="block w-2 h-2 bg-blue-darker rounded-full focus:outline-none" />
            </SliderRoot>
            <div class="text-sm text-gray-500 w-12 text-center">
                {{ formattedDuration }}
            </div>
        </div>
        <div v-else>
            <span class="uppercase font-display text-blue-darker">{{ $t('Play story') }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <button @click="playPause" type="button" class="focus:outline-none bg-blue-darker rounded-full p-1">
                <!-- Play Icon -->
                <svg
                    v-if="!isPlaying"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 256"
                    class="w-6 h-6 stroke-blue-lightest fill-none"
                >
                    <path
                        d="M72,39.88V216.12a8,8,0,0,0,12.15,6.69l144.08-88.12a7.82,7.82,0,0,0,0-13.38L84.15,33.19A8,8,0,0,0,72,39.88Z"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="16"
                    />
                </svg>
                <!-- Pause Icon -->
                <svg
                    v-if="isPlaying"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 256"
                    class="w-6 h-6 stroke-blue-lightest fill-none"
                >
                    <rect
                        x="152"
                        y="40"
                        width="56"
                        height="176"
                        rx="8"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="16"
                    />
                    <rect
                        x="48"
                        y="40"
                        width="56"
                        height="176"
                        rx="8"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="16"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { SliderRange, SliderRoot, SliderThumb, SliderTrack } from "radix-vue";
const props = defineProps(['index'])

const audio = ref(new Audio(`/audio/${props.index || 0}.mp3`));
const isPlaying = ref(false);
const currentTime = ref([0]);
const duration = ref(0);
const isSeeking = ref(false);
const wasStarted = ref(false);

const playPause = () => {
    if (isPlaying.value) {
        audio.value.pause();
    } else {
        wasStarted.value = true;
        audio.value.play();
    }
    isPlaying.value = !isPlaying.value;
};

const formatTime = (time) => {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60)
        .toString()
        .padStart(2, "0");
    return `${minutes}:${seconds}`;
};

const formattedTime = computed(() => formatTime(currentTime.value));
const formattedDuration = computed(() => formatTime(duration.value));

const seekUp = () => {
    isSeeking.value = true;
};

const seekDown = () => {
    isSeeking.value = false;
    audio.value.currentTime = currentTime.value[0];
};

audio.value.addEventListener("loadedmetadata", () => {
    duration.value = audio.value.duration;
});

audio.value.addEventListener("timeupdate", () => {
    if (isSeeking.value) return;
    currentTime.value = [audio.value.currentTime];
});

audio.value.addEventListener('ended', () => {
    isPlaying.value = false;
    wasStarted.value = false;
    audio.value.pause();
    audio.value.currentTime = 0;
});
</script>
