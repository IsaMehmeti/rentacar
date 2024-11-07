import { watch, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";

export function usePageTitle() {
    const route = useRoute();
    const { t } = useI18n();

    const pageTitle = computed(() => {
        if (route.meta.title) {
            return t(route.meta.title);
        }
        return "Default Title";
    });

    watch(
        pageTitle,
        (newTitle) => {
            document.title = `${newTitle} | Rent a Car Dushi`;
        },
        { immediate: true },
    );

    return pageTitle;
}
