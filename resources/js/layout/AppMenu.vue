<script setup>
import { computed, ref } from "vue";

import AppMenuItem from "./AppMenuItem.vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();

const model = computed(() => [
    {
        label: t("home"),
        items: [{ label: t("home"), icon: "pi pi-fw pi-home", to: "/" }],
    },
    {
        label: `${t("register", 2)} & ${t("client", 2)}`,
        items: [
            {
                label: t("register", 2),
                icon: "pi pi-fw pi-file-o",
                items: [
                    {
                        label: t("register", 2),
                        icon: "pi pi-fw pi-print",
                        to: "/contracts",
                    },
                    {
                        label: `${t("add")} ${t("register")}`,
                        icon: "pi pi-fw pi-plus",
                        to: "/contracts/create",
                    },
                ],
            },
            {
                label: t("client", 2),
                icon: "pi pi-fw pi-users",
                items: [
                    {
                        label: t("client", 2),
                        icon: "pi pi-fw pi-users",
                        to: "/clients",
                    },
                    // {
                    //     label: `${t("add")} ${t("client")}`,
                    //     icon: "pi pi-fw pi-user-plus",
                    //     to: "/clients/create",
                    // },
                ],
            },
        ],
    },
    {
        label: t("car", 2),
        items: [
            {
                label: t("car", 2),
                icon: "pi pi-fw pi-car",
                items: [
                    {
                        label: t("car", 2),
                        icon: "pi pi-fw pi-car",
                        to: "/cars",
                    },
                ],
            },
        ],
    },
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item
                v-if="!item.separator"
                :item="item"
                :index="i"
            ></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
