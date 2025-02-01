import api from "@/axios.js";

export const HomeService = {
    list: async () => {
        return await api().get("index");
    },
    byCar: async (id) => {
        return await api().get("by-car/" + id);
    },
};
