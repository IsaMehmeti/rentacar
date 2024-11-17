<script setup>
import { defineProps } from "vue";

const props = defineProps({
    for: {
        type: String,
        required: false,
    },
    required: Boolean,
});
</script>

<template>
    <label :for="for" class="block font-bold mb-3">
        <slot></slot>
        <span v-if="required" class="text-red-500 ml-0.5">*</span>
    </label>
</template>
