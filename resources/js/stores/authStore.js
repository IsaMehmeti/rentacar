import { defineStore } from "pinia";
import axios from "../axios.js";
import { AuthService } from "@/service/api/AuthService.js";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: null,
        token: localStorage.getItem("token") || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        async login(credentials) {
            try {
                const response = await AuthService.login({
                    email: credentials.email,
                    password: credentials.password,
                });
                this.token = response.data?.access_token;
                this.user = response.data?.user;
                localStorage.setItem("token", this.token);
            } catch (error) {
                throw error;
            }
        },

        logout() {
            this.token = null;
            this.user = null;
            localStorage.removeItem("token");
        },
    },
});
