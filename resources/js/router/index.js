import AppLayout from "@/layout/AppLayout.vue";
import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/authStore.js";
import { useTokenExpired } from "@/hooks/useTokenExpired.js";

const routes = [
    {
        path: "/",
        component: AppLayout,
        meta: { title: "home" },
        children: [
            {
                path: "/",
                name: "dashboard",
                meta: { title: "home", auth: true },
                component: () => import("@/views/Dashboard.vue"),
            },
            {
                path: "/contracts",
                name: "Contracts",
                meta: { title: "registers", auth: true },
                component: () =>
                    import("@/views/pages/contracts/Contracts.vue"),
            },
            {
                path: "/contracts/create",
                name: "Create Contract",
                meta: { title: "add-contract", auth: true },
                component: () =>
                    import("@/views/pages/contracts/Contracts.vue"),
            },
            {
                path: "/clients",
                name: "Clients",
                meta: { title: "clients", auth: true },
                component: () => import("@/views/pages/clients/Clients.vue"),
            },
            {
                path: "/clients/create",
                name: "Create Client",
                meta: { title: "add-client", auth: true },
                component: () =>
                    import("@/views/pages/clients/ClientCreate.vue"),
            },
            {
                path: "/cars",
                name: "Cars",
                meta: { title: "cars", auth: true },
                component: () => import("@/views/pages/cars/Cars.vue"),
            },
            {
                path: "/cars/create",
                name: "add-car",
                meta: { title: "Shto Veturen", auth: true },
                component: () => import("@/views/pages/cars/CarsCreate.vue"),
            },
            {
                path: "/uikit/formlayout",
                name: "formlayout",
                component: () => import("@/views/uikit/FormLayout.vue"),
            },
            {
                path: "/uikit/input",
                name: "input",
                component: () => import("@/views/uikit/InputDoc.vue"),
            },
            {
                path: "/uikit/button",
                name: "button",
                component: () => import("@/views/uikit/ButtonDoc.vue"),
            },
            {
                path: "/uikit/table",
                name: "table",
                component: () => import("@/views/uikit/TableDoc.vue"),
            },
            {
                path: "/uikit/list",
                name: "list",
                component: () => import("@/views/uikit/ListDoc.vue"),
            },
            {
                path: "/uikit/tree",
                name: "tree",
                component: () => import("@/views/uikit/TreeDoc.vue"),
            },
            {
                path: "/uikit/panel",
                name: "panel",
                component: () => import("@/views/uikit/PanelsDoc.vue"),
            },

            {
                path: "/uikit/overlay",
                name: "overlay",
                component: () => import("@/views/uikit/OverlayDoc.vue"),
            },
            {
                path: "/uikit/media",
                name: "media",
                component: () => import("@/views/uikit/MediaDoc.vue"),
            },
            {
                path: "/uikit/message",
                name: "message",
                component: () => import("@/views/uikit/MessagesDoc.vue"),
            },
            {
                path: "/uikit/file",
                name: "file",
                component: () => import("@/views/uikit/FileDoc.vue"),
            },
            {
                path: "/uikit/menu",
                name: "menu",
                component: () => import("@/views/uikit/MenuDoc.vue"),
            },
            {
                path: "/uikit/charts",
                name: "charts",
                component: () => import("@/views/uikit/ChartDoc.vue"),
            },
            {
                path: "/uikit/misc",
                name: "misc",
                component: () => import("@/views/uikit/MiscDoc.vue"),
            },
            {
                path: "/uikit/timeline",
                name: "timeline",
                component: () => import("@/views/uikit/TimelineDoc.vue"),
            },
            {
                path: "/pages/empty",
                name: "empty",
                component: () => import("@/views/pages/Empty.vue"),
            },
            {
                path: "/pages/crud",
                name: "crud",
                component: () => import("@/views/pages/Crud.vue"),
            },
            {
                path: "/documentation",
                name: "documentation",
                component: () => import("@/views/pages/Documentation.vue"),
            },
        ],
    },
    {
        path: "/landing",
        name: "landing",

        component: () => import("@/views/pages/Landing.vue"),
    },
    {
        path: "/pages/notfound",
        name: "notfound",
        component: () => import("@/views/pages/NotFound.vue"),
    },

    {
        path: "/login",
        name: "login",
        meta: { title: "Login" },
        component: () => import("@/views/pages/auth/Login.vue"),
    },
    {
        path: "/auth/access",
        name: "accessDenied",
        component: () => import("@/views/pages/auth/Access.vue"),
    },
    {
        path: "/auth/error",
        name: "error",
        component: () => import("@/views/pages/auth/Error.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} | Rent a Car Dushi`;
    if (to.meta.auth) {
        const authStore = useAuthStore();
        const token = authStore.token;
        if (!token || useTokenExpired(token)) {
            console.log("Logging Out");
            authStore.logout();
            next("/login");
        }
    }
    next();
});

export default router;
