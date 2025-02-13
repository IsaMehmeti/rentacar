import { createApp } from "vue";
import App from "./App.vue";

import Aura from "@primevue/themes/aura";
import PrimeVue from "primevue/config";
import ConfirmationService from "primevue/confirmationservice";
import ToastService from "primevue/toastservice";
import { VueQueryPlugin } from "@tanstack/vue-query";
import { createPinia } from "pinia";
import i18n from "@/utils/i18n.js";

import router from "./router";

import "@/assets/styles.scss";
import "@/assets/tailwind.css";

const app = createApp(App);

app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            darkModeSelector: ".app-dark",
        },
    },
});

app.use(router);
app.use(i18n);

app.use(VueQueryPlugin);
app.use(ToastService);
app.use(ConfirmationService);
app.use(createPinia());

app.mount("#app");
