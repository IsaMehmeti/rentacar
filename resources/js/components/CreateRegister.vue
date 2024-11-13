<script setup>
import LanguageSwitcher from "@/components/LanguageSwitcher.vue";
import { useI18n } from "vue-i18n";
import { computed, ref, watch } from "vue";
import { ClientService } from "@/service/api/ClientService.js";
import { useMutation, useQuery } from "@tanstack/vue-query";
import { CarService } from "@/service/api/CarService.js";
import createRegisterSchema from "@/schemas/registerSchema.js";
import createClientSchema from "@/schemas/clientSchema.js";
import { ErrorMessage, Field, Form } from "vee-validate";
import { RegisterService } from "@/service/api/RegisterService.js";
import moment from "moment";
import Label from "@/components/Label.vue";

const { t, locale } = useI18n();

const props = defineProps({
    visible: Boolean,
});
const emit = defineEmits(["update:visible", "save"]);
const clientCreateForm = ref(null);
const filteredClients = ref([]);
// const selectedClient = ref(props.client || {});
// const client = ref({});
const register = ref({
    client: {},
    days: 0,
    count_today: true,
});
const internalVisible = ref(props.visible);
//search client
const {
    mutate: searchClient,
    isPending: isSearching,
    data: searchData,
} = useMutation({
    mutationFn: async (name) => {
        return await ClientService.search(name);
    },
    onSuccess: async (data) => {
        filteredClients.value = data.data;
    },
    onError: (error) => {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: $("went-wrong"),
            life: 3000,
        });
    },
});

// list cars
const {
    isFetching: loadingCars,
    isError: errorCars,
    data: cars,
    error,
} = useQuery({
    queryKey: ["cars"],
    queryFn: async () => (await CarService.list()).data,
});

// create register
const {
    mutate: createNewRegister,
    isError: registerStoreIsError,
    error: registerStoreError,
} = useMutation({
    mutationKey: ["register-create", register],
    cacheTime: 0,
    staleTime: 0,
    mutationFn: async () => {
        register.value.start_date = register.value.dates[0];
        register.value.end_date = register.value.dates[1];
        return await RegisterService.store(register.value);
    },
    onError: (e) => {
        console.log(error);
    },
    onSuccess: (data) => {
        emit("save");
        handleClose();
        const blob = new Blob([data.data], { type: "application/pdf" });
        const fileURL = window.URL.createObjectURL(blob);

        const iframe = document.createElement("iframe");
        document.body.appendChild(iframe);

        iframe.style.display = "none";
        iframe.src = fileURL;
        iframe.onload = function () {
            setTimeout(function () {
                iframe.focus();
                iframe.contentWindow.print();
            }, 1);
        };
    },
});

// Watch `visible` prop to sync it with `internalVisible`
watch(
    () => props.visible,
    (newVal) => {
        internalVisible.value = newVal;
    },
);

// Watch `internalVisible` to emit changes to the parent
watch(internalVisible, (newVal) => {
    if (!newVal) emit("update:visible", false);
});

const handleClose = () => {
    register.value = {
        client: {},
        days: 0,
        count_today: true,
    };
    internalVisible.value = false;
};

const handleSearchClient = (e) => {
    searchClient(e.query);
};

const handleSelectClient = (selectedClient) => {
    register.value.client = selectedClient;
};

const calculateTotalPrice = () => {
    const price = Number(register.value.price_per_day ?? 0);
    if (isNaN(price)) {
        register.value.total_price = 0;
        return;
    }
    register.value.total_price = Number(register.value.days) * price;
};

const handleDateChange = () => {
    // if dates are cleared or only one date is selected clear days and dont calculate
    if (!register.value.dates || !register.value.dates[1]) {
        register.value.days = 0;
        register.value.count_today = true;
        return;
    }
    const date1 = moment(register.value.dates[0], "DD-MM-YYYY");
    const date2 = moment(register.value.dates[1], "DD-MM-YYYY");

    // Calculate difference in days
    let diffDays = date2.diff(date1, "days"); // Moment calculates difference in days

    // Add 1 day if count_today is checked
    if (register.value.count_today) {
        diffDays += 1;
    }

    // Set the days value
    register.value.days = diffDays;
};

watch(
    () => register.value.days,
    (newDays) => {
        calculateTotalPrice();
    },
);

const randomIntFromInterval = (min, max) => {
    // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
};

const schema = ref(createRegisterSchema(t).concat(createClientSchema(t)));
watch(locale, () => {
    schema.value = createRegisterSchema(t).concat(createClientSchema(t));
});

watch(
    () => register.value.client,
    (newClient) => {
        if (typeof newClient === "string") {
            // Treat as new client entry
            register.value.client = { full_name: newClient };
        }
    },
    { deep: true },
);
</script>

<template>
    <Dialog
        :visible="internalVisible"
        :dismissableMask="true"
        :modal="true"
        :style="{ width: '600px' }"
        @update:visible="handleClose"
    >
        <template #header>
            <div class="flex justify-between items-center w-full">
                <span class="text-xl font-bold">{{ t("add-contract") }}</span>
                <div class="flex items-center gap-2">
                    <LanguageSwitcher />
                </div>
            </div>
        </template>

        <Form
            ref="clientCreateForm"
            :validation-schema="schema"
            @submit="createNewRegister"
        >
            <div
                class="flex flex-col"
                :class="{
                    'mb-4': Object.hasOwn(register.client, 'id'),
                }"
            >
                <Label required>
                    <span v-if="Object.hasOwn(register.client, 'id')">
                        {{ t("the-client") }}
                    </span>
                    <span v-else>
                        {{ t("full_name") }}
                    </span>
                </Label>

                <Field
                    v-slot="{ values, errorMessage }"
                    v-model="register.client.full_name"
                    :validateOnChange="true"
                    name="full_name"
                    hidden
                >
                    <AutoComplete
                        v-model="register.client"
                        :suggestions="filteredClients"
                        :loading="isSearching"
                        optionLabel="full_name"
                        :placeholder="t('owner-placeholder')"
                        dropdown
                        :invalid="!!errorMessage"
                        display="chip"
                        @complete="handleSearchClient($event)"
                        @select="handleSelectClient($event)"
                    />
                    <ErrorMessage
                        class="text-red-500 text-m"
                        name="full_name"
                    />
                </Field>
            </div>

            <!--   Add new client     -->
            <div
                v-show="!Object.hasOwn(register.client, 'id')"
                class="flex flex-col gap-6 mt-4"
            >
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <Label for="id_card" required>{{ t("id_card") }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.client.id_card"
                            :validateOnChange="true"
                            name="id_card"
                        >
                            <InputText
                                id="id_card"
                                v-model="register.client.id_card"
                                :invalid="!!errorMessage"
                                class="w-full"
                                placeholder="1112223333"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="id_card"
                            />
                        </Field>
                    </div>
                    <div class="col-span-6">
                        <Label for="drivers_license_id" required>{{
                            t("drivers-license")
                        }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.client.drivers_license_id"
                            :validateOnChange="true"
                            name="drivers_license_id"
                        >
                            <InputText
                                id="drivers_license_id"
                                v-model="register.client.drivers_license_id"
                                :invalid="!!errorMessage"
                                class="w-full"
                                placeholder="DL11122233"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="drivers_license_id"
                            />
                        </Field>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <Label for="address">{{ t("address") }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.client.address"
                            :validateOnChange="true"
                            name="address"
                        >
                            <InputText
                                id="address"
                                v-model="register.client.address"
                                placeholder="Hasan Prishtina"
                                class="w-full"
                            />
                        </Field>
                    </div>
                    <div class="col-span-6">
                        <Label for="birthplace">{{ t("birthplace") }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.client.birthplace"
                            :validateOnChange="true"
                            name="birthplace"
                        >
                            <InputText
                                id="birthplace"
                                v-model="register.client.birthplace"
                                class="w-full"
                                placeholder="Ferizaj"
                            />
                        </Field>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <Label for="registration" required>{{
                            t("phone")
                        }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.client.phone"
                            :validateOnChange="true"
                            name="phone"
                        >
                            <InputText
                                id="phone"
                                v-model="register.client.phone"
                                :invalid="!!errorMessage"
                                class="w-full"
                                placeholder="+383 44 123 456"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="phone"
                            />
                        </Field>
                    </div>
                    <div class="col-span-6">
                        <Label for="birth">{{ t("birth") }} </Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.client.birth"
                            :validateOnChange="true"
                            name="birth"
                        >
                            <DatePicker
                                v-model="register.client.birth"
                                :showButtonBar="true"
                                :showIcon="true"
                                dateFormat="dd-mm-yy"
                                placeholder="dd-mm-yyyy"
                                class="w-full"
                            />
                        </Field>
                    </div>
                </div>
            </div>
            <!--   Add new client end     -->

            <!--    Rest   -->
            <hr class="mt-5 mb-5" />
            <div class="flex flex-col gap-6">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <Label for="car" required>{{ t("the-car") }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.car"
                            :validateOnChange="true"
                            name="car"
                        >
                            <Select
                                v-model="register.car"
                                :options="cars"
                                class="w-full"
                                :optionLabel="
                                    (car) =>
                                        `${car.model} ${car.color} - ${car.marsh}`
                                "
                                :placeholder="t('select-car')"
                                :invalid="!!errorMessage"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="car"
                            />
                        </Field>
                    </div>
                    <div class="col-span-6">
                        <Label for="fuel_status">{{ t("fuel_status") }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.fuel_status"
                            :validateOnChange="true"
                            name="color"
                        >
                            <InputText
                                id="color"
                                v-model="register.fuel_status"
                                :invalid="!!errorMessage"
                                :placeholder="t('fuel_status')"
                                class="w-full"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="fuel_status"
                            />
                        </Field>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-7">
                        <Label required for="dates">{{ t("dates") }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.dates"
                            :validateOnChange="true"
                            name="dates"
                        >
                            <DatePicker
                                v-model="register.dates"
                                selectionMode="range"
                                :manualInput="false"
                                :showButtonBar="true"
                                :showIcon="true"
                                dateFormat="dd-mm-yy"
                                class="w-full"
                                @update:modelValue="handleDateChange"
                                :placeholder="t('set-dates')"
                                :invalid="!!errorMessage"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="dates"
                            />
                        </Field>
                    </div>
                    <div class="col-span-2 text-center">
                        <Label for="count_today">{{ t("count_today") }}</Label>
                        <ToggleSwitch
                            class="mt-2"
                            v-model="register.count_today"
                            :disabled="!register.dates || !register.dates[1]"
                            @change="
                                () => {
                                    if (register.count_today) {
                                        register.days++;
                                    } else {
                                        register.days--;
                                    }
                                }
                            "
                        />
                    </div>

                    <div class="col-span-3">
                        <Label for="days">{{ t("days") }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.days"
                            :validateOnChange="true"
                            name="days"
                        >
                            <InputText
                                id="target"
                                v-model="register.days"
                                :invalid="!!errorMessage"
                                class="w-full"
                                disabled
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="days"
                            />
                        </Field>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <Label required for="price_per_day">{{
                            t("price_per_day")
                        }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.price_per_day"
                            :validateOnChange="true"
                            name="price_per_day"
                        >
                            <InputText
                                id="target"
                                v-model="register.price_per_day"
                                :invalid="!!errorMessage"
                                class="w-full"
                                :placeholder="0"
                                @input="calculateTotalPrice"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="price_per_day"
                            />
                        </Field>
                    </div>
                    <div class="col-span-6">
                        <Label required for="total_price">{{
                            t("total_price")
                        }}</Label>
                        <Field
                            v-slot="{ values, errorMessage }"
                            v-model="register.total_price"
                            :validateOnChange="true"
                            name="total_price"
                        >
                            <InputNumber
                                id="total_price"
                                v-model="register.total_price"
                                :invalid="!!errorMessage"
                                class="w-full"
                                placeholder="$"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="total_price"
                            />
                        </Field>
                    </div>
                </div>
            </div>
            <div class="float-end mt-4">
                <Button
                    :label="t('cancel')"
                    icon="pi pi-times"
                    text
                    @click="handleClose"
                />
                <Button
                    :label="t('save')"
                    class="ml-3.5"
                    icon="pi pi-check"
                    type="submit"
                />
            </div>
        </Form>
        <!-- Add a clear div -->
        <div style="clear: both"></div>
        <Message
            v-for="error in registerStoreError?.response?.data?.errors"
            v-if="registerStoreError?.status === 422"
            class="mt-2 mb-2"
            icon="pi pi-times-circle"
            severity="error"
        >
            {{ error[0] }}
        </Message>
    </Dialog>
</template>
