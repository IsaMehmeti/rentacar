<script setup>
import { usePageTitle } from "./hooks/usePageTitle.js";

// Translates page titles
// use translation keys in router meta names to automatically translate
usePageTitle();
</script>

<template>
    <router-view />
</template>

<style scoped></style>
