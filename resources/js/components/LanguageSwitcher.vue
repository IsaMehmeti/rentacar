<script setup>
import { ref, watch } from "vue";
import { useLocale } from "@/hooks/useLocale.js";

const languages = ref(["EN", "SQ"]);
const selectedLanguage = ref(
    localStorage.getItem("locale") === "en" ? "EN" : "SQ",
);
const { changeLocale } = useLocale();

watch(selectedLanguage, (newLang) => {
    changeLocale(newLang.toLowerCase());
});
</script>

<template>
    <Dropdown v-model="selectedLanguage" :options="languages" />
</template>
