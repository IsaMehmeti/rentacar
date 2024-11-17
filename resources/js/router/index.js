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
                component: () => import("@/views/Registers.vue"),
            },
            {
                path: "/clients",
                name: "Clients",
                meta: { title: "clients", auth: true },
                component: () => import("@/views/Clients.vue"),
            },
            {
                path: "/cars",
                name: "Cars",
                meta: { title: "cars", auth: true },
                component: () => import("@/views/Cars.vue"),
            },
        ],
    },
    {
        path: "/login",
        name: "login",
        meta: { title: "Login" },
        component: () => import("@/views/pages/auth/Login.vue"),
    },
    {
        path: "/:pathMatch(.*)*",
        name: "not-found",
        meta: { title: "not-found" },
        component: () => import("@/views/pages/NotFound.vue"),
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
