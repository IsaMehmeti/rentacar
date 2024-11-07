import { useI18n } from "vue-i18n";

export function useLocale() {
    const { locale } = useI18n({ useScope: "global" });

    const changeLocale = (newLocale) => {
        locale.value = newLocale;
        document.documentElement.lang = newLocale;
        localStorage.setItem("locale", newLocale);
    };

    return {
        changeLocale,
        locale,
    };
}
