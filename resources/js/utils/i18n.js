import { createI18n } from "vue-i18n";
import en from "../locales/en.json";
import sq from "../locales/sq.json";

const locale = localStorage.getItem("locale") || "sq";
document.documentElement.lang = locale;
export const i18n = createI18n({
    legacy: false,
    locale,
    fallbackLocale: "sq",
    messages: {
        sq,
        en,
    },
});

export default i18n;
