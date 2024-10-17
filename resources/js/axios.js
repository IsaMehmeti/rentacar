import axios from "axios";
import { useAuthStore } from "@/stores/authStore.js";
/**
 * Initialize Axios
 */
const api = axios.create({
    baseURL: `${import.meta.env.VITE_BACKEND_URL ?? "http://127.0.0.1:8000"}/api`, // Set your API base URL in .env
    headers: {
        "Content-Type": "application/json",
    },
});

// Add a request interceptor to attach the token if it exists
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("token"); // Adjust based on your token storage
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error),
);

// Add a response interceptor for error handling
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            // Handle unauthorized access (e.g., redirect to login, clear token)
            localStorage.removeItem("token");
            // Optional: Redirect to login page or emit event for global auth handling
        }
        return Promise.reject(error);
    },
);

// Add a request interceptor
api.interceptors.request.use(
    (config) => {
        const authStore = useAuthStore();
        if (authStore.token) {
            config.headers.Authorization = `Bearer ${authStore.token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    },
);
// export default api;
export default () => {
    return api;
};
