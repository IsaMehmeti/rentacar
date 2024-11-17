import api from "@/axios.js";

export const HomeService = {
    list: async () => {
        return await api().get("index");
    },
};
