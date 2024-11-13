<script setup>
import { FilterMatchMode } from "@primevue/core/api";
import { useToast } from "primevue/usetoast";
import { computed, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { CarService } from "@/service/api/CarService.js";
import { useMutation, useQuery, useQueryClient } from "@tanstack/vue-query";
import { useI18n } from "vue-i18n";
import LanguageSwitcher from "@/components/LanguageSwitcher.vue";
import * as yup from "yup";
import { ErrorMessage, Field, Form } from "vee-validate";
import { RegisterService } from "@/service/api/RegisterService.js";

const toast = useToast();
const queryClient = useQueryClient();

const { t, locale } = useI18n();
const route = useRoute();

const pageTitle = computed(() => t(route.meta.title));

const dt = ref();
const carDialog = ref(false);
const carUpdateDialog = ref(false);
const submitted = ref(false);
const deleteCarDialog = ref(false);

const product = ref({});
const car = ref({});
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const carCreateForm = ref(null);
const carTransmissions = ref(["automatik", "manual"]);

const formattedCarTransmissions = computed(() =>
    carTransmissions.value.map((transmission) => ({
        label: transmission, // This is what the user sees in the dropdown
        value: transmission, // This is the actual value set in `car.marsh`
    })),
);

// list cars
const { isFetching, isError, data, error, refetch } = useQuery({
    queryKey: ["registers"],
    queryFn: async () => (await RegisterService.list()).data,
});

//delete car
const { mutate: deleteCar, isPending: isDeleting } = useMutation({
    mutationFn: async (id) => {
        await CarService.delete(id);
    },
    onSuccess: async () => {
        const id = car.value.id;
        queryClient.setQueryData(["cars"], (oldData) => {
            const newData = [...oldData];
            newData.splice(
                data.value.findIndex((c) => c.id === id),
                1,
            );
            return newData;
        });
        toast.add({
            severity: "success",
            summary: t("success"),
            detail: $("delete-success"),
            life: 3000,
        });
        car.value = {};
    },
    onError: (error) => {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: $("went-wrong"),
            life: 3000,
        });
        console.log(error);
        car.value = {};
    },
});

// create car
const {
    mutate: storeNewCar,
    isError: carStoreIsError,
    error: carStoreError,
} = useMutation({
    mutationKey: ["cars", car],
    cacheTime: 0,
    staleTime: 0,
    mutationFn: async () => {
        return await CarService.store(car.value);
    },
    onError: (e) => {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: $("went-wrong"),
            life: 3000,
        });
        console.log(error);
    },
    onSuccess: (data) => {
        // queryClient.setQueryData(["cars"], (oldData) => {
        //     return [...oldData, data?.data?.data];
        // });
        car.value = {};
        carDialog.value = false;
        refetch();
    },
});

// update car
const {
    mutate: updateCar,
    isError: carUpdateIsError,
    error: carUpdateError,
} = useMutation({
    mutationKey: ["cars-update", car],
    cacheTime: 0,
    staleTime: 0,
    mutationFn: async () => {
        return await CarService.update(car.value, car.value.id);
    },
    onError: (e) => {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: $("went-wrong"),
            life: 3000,
        });
        console.log(error);
    },
    onSuccess: (data) => {
        // queryClient.setQueryData(["cars"], (oldData) => {
        //     return [...oldData, data?.data?.data];
        // });
        car.value = {};
        carUpdateDialog.value = false;
        refetch();
    },
});

function confirmDeleteCar(selectedCar) {
    car.value = selectedCar;
    deleteCarDialog.value = true;
}

function handleDeleteCar() {
    deleteCarDialog.value = false;
    //delete car method we get from useMutation
    deleteCar(car.value.id);
}

// open create car modal
function openNew() {
    car.value = {};
    submitted.value = false;
    carDialog.value = true;
}
function editCar(selectedCar) {
    car.value = { ...selectedCar };
    carUpdateDialog.value = true;
}

function hideCreateCarDialog() {
    carDialog.value = false;
}

const schema = ref(createSchema(t)); // Initialize schema with the current locale

function createSchema(t) {
    return yup.object({
        model: yup
            .string()
            .required(t("validation.required", { field: t("model") })),
        marsh: yup
            .string()
            .required(t("validation.required", { field: t("marsh") })),
        production_year: yup
            .number()
            .required(t("validation.required", { field: t("production") })),
        target: yup
            .string()
            .required(t("validation.required", { field: t("target") })),
        shasi_nr: yup
            .string()
            .required(t("validation.required", { field: t("shasi") })),
        color: yup
            .string()
            .required(t("validation.required", { field: t("color") })),
        comment: yup.string().nullable(),
        technical_control: yup.date().nullable(),
        registration: yup.date().nullable(),
    });
}
// Watching for changes in the locale to recreate the schema when translation occurs
watch(locale, () => {
    schema.value = createSchema(t);
});
</script>

<template>
    <div>
        <!--   List car table start     -->
        <div class="card">
            <div class="font-semibold text-xl mb-4">{{ pageTitle }}</div>

            <DataTable
                v-if="!isError"
                ref="dt"
                :currentPageReportTemplate="`${t('displaying')} {first} ${t('to')} {last} ${t('from')} {totalRecords} ${t('cars')}`"
                :filters="filters"
                :loading="isFetching || isDeleting"
                :paginator="true"
                :rowHover="true"
                :rows="10"
                :rowsPerPageOptions="[5, 10, 25]"
                :value="data"
                dataKey="id"
                paginatorTemplate="PrevPageLink PageLinks NextPageLink CurrentPageReport RowsPerPageDropdown"
                showGridlines
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <Button
                            :label="t('add-register')"
                            class="mr-2"
                            icon="pi pi-plus"
                            severity="secondary"
                            @click="openNew"
                        />
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText
                                v-model="filters['global'].value"
                                :placeholder="`${t('search')}...`"
                            />
                        </IconField>
                    </div>
                </template>

                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            class="mr-2"
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            @click="editCar(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteCar(slotProps.data)"
                        />
                    </template>
                </Column>
                <Column
                    :header="t('color')"
                    field="color"
                    hidden
                    style="min-width: 16rem"
                ></Column>
                <template #empty> {{ t("no-data-found") }} </template>
            </DataTable>
        </div>
        <!--   List car table end     -->

        <!--   Create car dialog start     -->
        <Dialog
            v-model:visible="carDialog"
            :dismissableMask="true"
            :modal="true"
            :style="{ width: '500px' }"
        >
            <template #header>
                <div class="flex justify-between items-center w-full">
                    <span class="text-xl font-bold">{{ t("add-car") }}</span>
                    <div class="flex items-center gap-2">
                        <LanguageSwitcher />
                    </div>
                </div>
            </template>
            <Form
                ref="carCreateForm"
                :validation-schema="schema"
                @submit="storeNewCar"
            >
                <div class="flex flex-col gap-6">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="model">{{
                                t("model")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.model"
                                :validateOnChange="true"
                                name="model"
                            >
                                <InputText
                                    id="model"
                                    v-model="car.model"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="Golf 7"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="model"
                                />
                            </Field>
                        </div>

                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="marsh">{{
                                t("marsh")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.marsh"
                                :validateOnChange="true"
                                name="marsh"
                            >
                                <Select
                                    v-model="car.marsh"
                                    :invalid="!!errorMessage"
                                    :options="formattedCarTransmissions"
                                    :placeholder="t('select-one')"
                                    class="w-full"
                                    optionLabel="label"
                                    optionValue="value"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="marsh"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="color">{{
                                t("color")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.color"
                                :validateOnChange="true"
                                name="color"
                            >
                                <InputText
                                    id="color"
                                    v-model="car.color"
                                    :invalid="!!errorMessage"
                                    :placeholder="t('blue')"
                                    class="w-full"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="color"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <label
                                class="block font-bold mb-3"
                                for="production_year"
                                >{{ t("production") }}</label
                            >
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.production_year"
                                :validateOnChange="true"
                                name="production_year"
                            >
                                <InputText
                                    id="production_year"
                                    v-model="car.production_year"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="2015"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="production_year"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="target">{{
                                t("target")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.target"
                                :validateOnChange="true"
                                name="target"
                            >
                                <InputText
                                    id="target"
                                    v-model="car.target"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="05 999 AA"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="target"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="shasi">{{
                                t("shasi")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.shasi_nr"
                                :validateOnChange="true"
                                name="shasi_nr"
                            >
                                <InputText
                                    id="shasi_nr"
                                    v-model="car.shasi_nr"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="WVWZZZAUZDP003950"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="shasi_nr"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label
                                class="block font-bold mb-3"
                                for="registration"
                                >{{ t("registration") }}</label
                            >
                            <Field
                                v-slot="{ values }"
                                v-model="car.registration"
                                :validateOnChange="true"
                                name="registration"
                            >
                                <DatePicker
                                    v-model="car.registration"
                                    :showButtonBar="true"
                                    :showIcon="true"
                                    dateFormat="dd-mm-yy"
                                    placeholder="dd-mm-yyyy"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="registration"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <label
                                class="block font-bold mb-3"
                                for="technical_control"
                                icon="pi pi-arrow-left"
                                >{{ t("technical-control") }} (<span
                                    v-if="car.owner"
                                >
                                    {{ t("yearly") }}
                                </span>
                                <span v-else> {{ t("6-month") }} </span>)
                            </label>
                            <Field
                                v-slot="{ values }"
                                v-model="car.technical_control"
                                :validateOnChange="true"
                                name="technical_control"
                            >
                                <DatePicker
                                    v-model="car.technical_control"
                                    :showButtonBar="true"
                                    :showIcon="true"
                                    ateFormat="dd-mm-yy"
                                    placeholder="dd-mm-yyyy"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="technical_control"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="owner">{{
                                t("owner")
                            }}</label>
                            <Field
                                v-slot="{ values }"
                                v-model="car.owner"
                                :validateOnChange="true"
                                name="owner"
                            >
                                <InputText
                                    id="owner"
                                    v-model="car.owner"
                                    :placeholder="t('owner-placeholder')"
                                    class="w-full"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="registration"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <Message icon="pi pi-arrow-left" severity="info">{{
                                t("owner-info")
                            }}</Message>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-3" for="comment">{{
                            t("comment")
                        }}</label>
                        <Field
                            v-slot="{ values }"
                            v-model="car.comment"
                            :validateOnChange="true"
                            name="comment"
                        >
                            <Textarea
                                id="comment"
                                v-model="car.comment"
                                class="w-full"
                                cols="20"
                                rows="3"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="comment"
                            />
                        </Field>
                    </div>
                </div>
                <div class="float-end mt-4">
                    <Button
                        :label="t('cancel')"
                        icon="pi pi-times"
                        text
                        @click="hideCreateCarDialog"
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
                v-for="error in carStoreError.response.data.errors"
                v-if="carStoreError?.status === 422"
                class="mt-2 mb-2"
                icon="pi pi-times-circle"
                severity="error"
            >
                {{ error[0] }}
            </Message>
        </Dialog>
        <!--   Create car dialog end     -->

        <!--   Update car dialog start     -->
        <Dialog
            v-model:visible="carUpdateDialog"
            :dismissableMask="true"
            :modal="true"
            :style="{ width: '500px' }"
        >
            <template #header>
                <div class="flex justify-between items-center w-full">
                    <span class="text-xl font-bold">{{ t("update-car") }}</span>
                    <div class="flex items-center gap-2">
                        <LanguageSwitcher />
                    </div>
                </div>
            </template>
            <Form
                ref="carCreateForm"
                :validation-schema="schema"
                @submit="updateCar"
            >
                <div class="flex flex-col gap-6">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="model">{{
                                t("model")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.model"
                                :validateOnChange="true"
                                name="model"
                            >
                                <InputText
                                    id="model"
                                    v-model="car.model"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="Golf 7"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="model"
                                />
                            </Field>
                        </div>

                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="marsh">{{
                                t("marsh")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.marsh"
                                :validateOnChange="true"
                                name="marsh"
                            >
                                <Select
                                    v-model="car.marsh"
                                    :invalid="!!errorMessage"
                                    :options="formattedCarTransmissions"
                                    :placeholder="t('select-one')"
                                    class="w-full"
                                    optionLabel="label"
                                    optionValue="value"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="marsh"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="color">{{
                                t("color")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.color"
                                :validateOnChange="true"
                                name="color"
                            >
                                <InputText
                                    id="color"
                                    v-model="car.color"
                                    :invalid="!!errorMessage"
                                    :placeholder="t('blue')"
                                    class="w-full"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="color"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <label
                                class="block font-bold mb-3"
                                for="production_year"
                                >{{ t("production") }}</label
                            >
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.production_year"
                                :validateOnChange="true"
                                name="production_year"
                            >
                                <InputText
                                    id="production_year"
                                    v-model="car.production_year"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="2015"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="production_year"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="target">{{
                                t("target")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.target"
                                :validateOnChange="true"
                                name="target"
                            >
                                <InputText
                                    id="target"
                                    v-model="car.target"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="05 999 AA"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="target"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="shasi">{{
                                t("shasi")
                            }}</label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="car.shasi_nr"
                                :validateOnChange="true"
                                name="shasi_nr"
                            >
                                <InputText
                                    id="shasi_nr"
                                    v-model="car.shasi_nr"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="WVWZZZAUZDP003950"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="shasi_nr"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label
                                class="block font-bold mb-3"
                                for="registration"
                                >{{ t("registration") }}</label
                            >
                            <Field
                                v-slot="{ values }"
                                v-model="car.registration"
                                :validateOnChange="true"
                                name="registration"
                            >
                                <DatePicker
                                    v-model="car.registration"
                                    :showButtonBar="true"
                                    :showIcon="true"
                                    dateFormat="dd-mm-yy"
                                    placeholder="dd-mm-yyyy"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="registration"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <label
                                class="block font-bold mb-3"
                                for="technical_control"
                                >{{ t("technical-control") }} (<span
                                    v-if="car.owner"
                                >
                                    {{ t("yearly") }}
                                </span>
                                <span v-else> {{ t("6-month") }} </span>)</label
                            >
                            <Field
                                v-slot="{ values }"
                                v-model="car.technical_control"
                                :validateOnChange="true"
                                name="technical_control"
                            >
                                <DatePicker
                                    v-model="car.technical_control"
                                    :showButtonBar="true"
                                    :showIcon="true"
                                    ateFormat="dd-mm-yy"
                                    placeholder="dd-mm-yyyy"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="technical_control"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block font-bold mb-3" for="owner">{{
                                t("owner")
                            }}</label>
                            <Field
                                v-slot="{ values }"
                                v-model="car.owner"
                                :validateOnChange="true"
                                name="owner"
                            >
                                <InputText
                                    id="owner"
                                    v-model="car.owner"
                                    :placeholder="t('owner-placeholder')"
                                    class="w-full"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="owner"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <Message icon="pi pi-arrow-left" severity="info">{{
                                t("owner-info")
                            }}</Message>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-3" for="comment">{{
                            t("comment")
                        }}</label>
                        <Field
                            v-slot="{ values }"
                            v-model="car.comment"
                            :validateOnChange="true"
                            name="comment"
                        >
                            <Textarea
                                id="comment"
                                v-model="car.comment"
                                class="w-full"
                                cols="20"
                                rows="3"
                            />
                            <ErrorMessage
                                class="text-red-500 text-m mt-2"
                                name="comment"
                            />
                        </Field>
                    </div>
                </div>
                <div class="float-end mt-4">
                    <Button
                        :label="t('cancel')"
                        icon="pi pi-times"
                        text
                        @click="(carUpdateDialog = false), (car = {})"
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
                v-for="error in carUpdateError.response.data.errors"
                v-if="carUpdateError?.status === 422"
                class="mt-2 mb-2"
                icon="pi pi-times-circle"
                severity="error"
            >
                {{ error[0] }}
            </Message>
        </Dialog>
        <!--   Create car dialog end     -->

        <!--   Delete car dialog     -->
        <Dialog
            v-model:visible="deleteCarDialog"
            :dismissableMask="true"
            :header="t('confirm')"
            :modal="true"
            :style="{ width: '450px' }"
        >
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="product"
                    >{{ t("are-sure-u-want-to-delete") }}:
                    <b>{{ car.model }} - {{ car.color }}</b
                    >?</span
                >
            </div>
            <template #footer>
                <Button
                    :label="t('no')"
                    icon="pi pi-times"
                    text
                    @click="deleteCarDialog = false"
                />
                <Button
                    :label="t('yes')"
                    icon="pi pi-check"
                    @click="handleDeleteCar"
                />
            </template>
        </Dialog>
        <!--   Delete car dialog end     -->
    </div>
</template>
