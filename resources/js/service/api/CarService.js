import api from "@/axios.js";

export const CarService = {
    list: async () => {
        return await api().get("cars");
    },
    store: async (data) => {
        return await api().post("cars", data);
    },
    update: async (data, id) => {
        return await api().patch(`cars/${id}`, data);
    },
    delete: async (id) => {
        return await api().delete(`cars/${id}`);
    },
};
