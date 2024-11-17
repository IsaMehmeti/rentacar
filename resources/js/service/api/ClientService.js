import api from "@/axios.js";

export const ClientService = {
    list: async () => {
        return await api().get("clients");
    },
    store: async (data) => {
        return await api().post("clients", data);
    },
    update: async (data, id) => {
        return await api().patch(`clients/${id}`, data);
    },
    delete: async (id) => {
        return await api().delete(`clients/${id}`);
    },
    search: async (name) => {
        return await api().get("clients-search", {
            params: {
                name,
            },
        });
    },
};
