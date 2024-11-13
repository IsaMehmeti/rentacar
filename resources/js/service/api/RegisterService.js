import api from "@/axios.js";

export const RegisterService = {
    list: async () => {
        return await api().get("registers");
    },
    store: async (data) => {
        return await api().post("registers", data, {
            responseType: "blob",
        });
    },
    print: async (id, data = {}) => {
        return await api().post(`registers-print/${id}`, data, {
            responseType: "blob",
        });
    },
    update: async (data, id) => {
        return await api().patch(`registers/${id}`, data);
    },
    delete: async (id) => {
        return await api().delete(`registers/${id}`);
    },
};
