import api from "@/axios.js";

export const AuthService = {
    login: async (data) => {
        return await api().post("login", data);
    },
};
