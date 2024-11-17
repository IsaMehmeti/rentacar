<script setup>
import { useLayout } from "@/layout/composables/layout";
import { onMounted, ref, watch } from "vue";
import { useMutation, useQuery, useQueryClient } from "@tanstack/vue-query";
import { HomeService } from "@/service/api/HomeService.js";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
import CreateRegister from "@/components/CreateRegister.vue";
import { RegisterService } from "@/service/api/RegisterService.js";
import { FilterMatchMode } from "@primevue/core/api";
import LanguageSwitcher from "@/components/LanguageSwitcher.vue";
import { Form, Field, ErrorMessage } from "vee-validate";

const { getPrimary, getSurface, isDarkTheme } = useLayout();

const { t, locale } = useI18n();
const route = useRoute();
const queryClient = useQueryClient();

//car and client for popover
const car = ref({});
const client = ref({});
const carPopover = ref(null);
const clientPopover = ref(null);

const revenueHidden = ref(true);
const registersHidden = ref(true);
const registerCreateDialog = ref(false);
const registerUpdateDialog = ref(false);
const register = ref({});
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const isPrinting = ref(false);
const deleteRegisterDialog = ref(false);

const formatCurrency = (value) => {
    return value?.toLocaleString(locale.value, {
        style: "currency",
        currency: "EUR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });
};

const handlePrintRegister = (selectedRegister) => {
    register.value = selectedRegister;
    isPrinting.value = register.value.id;
    printRegister();
};
// print register
const {
    mutate: printRegister,
    isError: registerStoreIsError,
    error: registerStoreError,
} = useMutation({
    mutationKey: ["register-print", register],
    cacheTime: 0,
    staleTime: 0,
    mutationFn: async () => {
        return await RegisterService.print(register.value.id);
    },
    onError: (e) => {
        console.log(error);
    },
    onSuccess: (data) => {
        const blob = new Blob([data.data], { type: "application/pdf" });
        const fileURL = window.URL.createObjectURL(blob);
        isPrinting.value = false;

        const iframe = document.createElement("iframe");
        document.body.appendChild(iframe);

        iframe.style.display = "none";
        iframe.src = fileURL;
        iframe.onload = () => {
            iframe.focus();
            iframe.contentWindow.print();
        };
    },
});

// list Home
const { data: homeData, refetch: refetchHome } = useQuery({
    queryKey: ["home"],
    queryFn: async () => (await HomeService.list()).data,
});

//delete register
const { mutate: deleteRegister, isPending: isDeleting } = useMutation({
    mutationFn: async (id) => {
        await RegisterService.delete(id);
    },
    onSuccess: async () => {
        const id = register.value.id;
        queryClient.setQueryData(["registers-dashboard"], (oldData) => {
            const newData = [...oldData];
            newData.splice(
                data.value.findIndex((c) => c.id === id),
                1,
            );
            return newData;
        });

        register.value = {};
    },
    onError: (error) => {
        console.log(error);
        register.value = {};
    },
});

function handleDeleteRegister() {
    deleteRegisterDialog.value = false;
    //delete car method we get from useMutation
    deleteRegister(register.value.id);
}

function confirmDeleteCar(selectedRegister) {
    register.value = selectedRegister;
    deleteRegisterDialog.value = true;
}
function openCarPopover(event, selectedCar) {
    carPopover.value.toggle(event);
    car.value = selectedCar;
}
function openClientPopover(event, selectedClient) {
    clientPopover.value.toggle(event);
    client.value = selectedClient;
}
// list registers
const {
    isFetching: isFetchingRegisters,
    isError,
    data: data,
    error,
    refetch: refetchRegisters,
} = useQuery({
    queryKey: ["registers-dashboard"],
    queryFn: async () => (await RegisterService.list({ take: 40 })).data,
});

// update register
const {
    mutate: updateRegister,
    isError: registerUpdateIsError,
    error: registerUpdateError,
} = useMutation({
    mutationKey: ["register-update", register],
    cacheTime: 0,
    staleTime: 0,
    mutationFn: async () => {
        return await RegisterService.update(register.value, register.value.id);
    },
    onError: (e) => {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: $("went-wrong"),
            life: 3000,
        });
        console.log(e);
    },
    onSuccess: (data) => {
        register.value = {};
        registerUpdateDialog.value = false;
        refetch();
    },
});
</script>

<template>
    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 lg:col-span-6 xl:col-span-6">
            <div class="card mb-0">
                <div class="flex justify-between mb-4">
                    <div>
                        <span
                            class="block text-muted-color font-medium mb-4 text-xl"
                            >{{ t("registers-count") }}</span
                        >
                        <div
                            class="text-surface-900 dark:text-surface-0 font-medium text-3xl"
                            :class="{ 'blur-md': registersHidden }"
                        >
                            {{ homeData?.data?.counts?.total }}
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-center bg-blue-100 dark:bg-blue-400/10 rounded-border"
                        style="width: 4.5rem; height: 4.5rem"
                    >
                        <i class="pi pi-file text-blue-500 !text-3xl"></i>
                    </div>
                </div>
                <div class="flex justify-between items-stretch">
                    <div>
                        <span class="text-lg text-muted-color"
                            >{{ t("today") }}:
                        </span>
                        <span
                            class="text-lg text-primary font-medium"
                            :class="{ 'blur-md': registersHidden }"
                        >
                            {{ homeData?.data?.counts?.today }}
                        </span>
                        <br />
                        <span class="text-lg text-muted-color"
                            >{{ t("this-month") }}:
                        </span>
                        <span
                            class="text-lg text-primary font-medium"
                            :class="{ 'blur-md': registersHidden }"
                        >
                            {{ homeData?.data?.counts?.monthly }}
                        </span>
                        <br />
                        <span class="text-lg text-muted-color"
                            >{{ t("this-year") }}:
                        </span>
                        <span
                            class="text-lg text-primary font-medium"
                            :class="{ 'blur-md': registersHidden }"
                        >
                            {{ homeData?.data?.counts?.yearly }}
                        </span>
                    </div>

                    <div class="flex flex-col">
                        <ToggleButton
                            style="margin-top: 35%"
                            v-model="registersHidden"
                            onIcon="pi pi-lock"
                            offIcon="pi pi-lock-open"
                            :onLabel="t('show')"
                            :offLabel="t('hide')"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6 xl:col-span-6">
            <div class="card mb-0">
                <div class="flex justify-between mb-4">
                    <div>
                        <span
                            class="block text-muted-color font-medium mb-4 text-xl"
                            >{{ t("revenue") }}</span
                        >
                        <div
                            class="text-surface-900 dark:text-surface-0 font-medium text-3xl"
                            :class="{
                                'blur-md': revenueHidden,
                            }"
                        >
                            {{ formatCurrency(homeData?.data?.total) }}
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-center bg-orange-100 dark:bg-orange-400/10 rounded-border"
                        style="width: 4.5rem; height: 4.5rem"
                    >
                        <i class="pi pi-dollar text-orange-500 !text-3xl"></i>
                    </div>
                </div>

                <div class="flex justify-between items-stretch">
                    <div>
                        <span class="text-lg text-muted-color"
                            >{{ t("today") }}:</span
                        >
                        <span
                            class="text-lg text-primary font-medium"
                            :class="{ 'blur-md': revenueHidden }"
                        >
                            {{ formatCurrency(homeData?.data?.today) }}
                        </span>
                        <br />
                        <span class="text-lg text-muted-color"
                            >{{ t("this-month") }}:
                        </span>
                        <span
                            class="text-lg text-primary font-medium"
                            :class="{ 'blur-md': revenueHidden }"
                        >
                            {{ formatCurrency(homeData?.data?.monthly) }}
                        </span>
                        <br />
                        <span class="text-lg text-muted-color"
                            >{{ t("this-year") }}:</span
                        >
                        <span
                            class="text-lg text-primary font-medium"
                            :class="{ 'blur-md': revenueHidden }"
                        >
                            {{ formatCurrency(homeData?.data?.yearly) }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <ToggleButton
                            style="margin-top: 35%"
                            v-model="revenueHidden"
                            onIcon="pi pi-lock"
                            offIcon="pi pi-lock-open"
                            :onLabel="t('show')"
                            :offLabel="t('hide')"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-12">
            <div class="card">
                <div class="font-semibold text-xl mb-4">
                    {{ t("recent-registers") }}
                    <Button
                        :label="t('add-register')"
                        class="ml-2"
                        icon="pi pi-plus"
                        severity="secondary"
                        @click="registerCreateDialog = true"
                    />
                </div>
                <DataTable
                    v-if="!isError"
                    :loading="isFetchingRegisters"
                    :value="data"
                    :filters="filters"
                    :rows="8"
                    :paginator="true"
                    responsiveLayout="scroll"
                >
                    <template #empty> {{ t("no-data-found") }} </template>
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
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

                    <Column
                        :header="t('start-date')"
                        field="start_date"
                        style="min-width: 10rem; text-align: center"
                        sortable
                    >
                        <template #body="slotProps">
                            <Chip>
                                {{ slotProps.data.start_date }}
                                <span class="text-primary">
                                    {{ slotProps.data.created_time?.time }}
                                </span>
                            </Chip>
                        </template>
                    </Column>
                    <Column
                        :header="t('end-date')"
                        field="end_date"
                        style="min-width: 10rem; text-align: center"
                        sortable
                    >
                        <template #body="slotProps">
                            <Chip>
                                {{ slotProps.data.end_date }}
                                <span class="text-primary">
                                    {{ slotProps.data.created_time?.time }}
                                </span>
                            </Chip>
                        </template>
                    </Column>
                    <Column
                        :header="t('status')"
                        field="status"
                        style="min-width: 2rem; text-align: center"
                        sortable
                    >
                        <template #body="slotProps">
                            <Tag
                                v-show="slotProps.data.status === 'in_progress'"
                                severity="warn"
                            >
                                <i
                                    v-tooltip="t('in-progress')"
                                    class="pi pi-spinner spinner"
                                    style="font-size: 2rem"
                            /></Tag>
                            <Tag
                                v-show="slotProps.data.status === 'completed'"
                                severity="success"
                            >
                                <i
                                    v-tooltip="t('completed')"
                                    class="pi pi-check-circle text-green-700"
                                    style="font-size: 2rem"
                                />
                            </Tag>
                            <Tag
                                v-show="slotProps.data.status === 'pending'"
                                severity="info"
                            >
                                <i
                                    v-tooltip="t('pending')"
                                    class="pi pi-clock"
                                    style="font-size: 2rem"
                                />
                            </Tag>
                        </template>
                    </Column>
                    <Column
                        :header="t('the-client')"
                        field="client.full_name"
                        sortable
                        style="min-width: 8rem"
                    >
                        <template #body="slotProps">
                            <Button
                                v-if="slotProps.data.client"
                                link
                                :label="`${slotProps.data.client.full_name}`"
                                @click="
                                    (event) =>
                                        openClientPopover(
                                            event,
                                            slotProps.data.client,
                                        )
                                "
                            />
                            <Popover
                                ref="clientPopover"
                                id="overlay_panel"
                                style="width: 250px"
                            >
                                <p class="font-bold text-xl">
                                    {{ client.full_name }}
                                </p>
                                <br />
                                <p>
                                    <span class="font-bold">{{
                                        t("address")
                                    }}</span
                                    >: {{ client.address }}
                                </p>

                                <p v-if="client.phone">
                                    <span class="font-bold">{{
                                        t("phone")
                                    }}</span
                                    >: {{ client.phone }}
                                </p>
                                <p v-if="client.passaport_nr">
                                    <span class="font-bold">{{
                                        t("passport_nr")
                                    }}</span
                                    >: {{ client.passaport_nr }}
                                </p>
                                <p v-if="client.id_card">
                                    <span class="font-bold">{{
                                        t("id_card")
                                    }}</span
                                    >: {{ client.id_card }}
                                </p>
                                <p v-if="client.birth">
                                    <span class="font-bold">{{
                                        t("birth")
                                    }}</span
                                    >:
                                    {{ client.birth }}
                                </p>
                                <p v-if="client.birthplace">
                                    <span class="font-bold">{{
                                        t("birthplace")
                                    }}</span
                                    >:
                                    {{ client.birthplace }}
                                </p>
                                <p v-if="client.drivers_license_id">
                                    <span class="font-bold">{{
                                        t("drivers-license")
                                    }}</span
                                    >:
                                    {{ client.drivers_license_id }}
                                </p>
                            </Popover>
                        </template>
                    </Column>
                    <Column
                        :header="t('the-car')"
                        field="car.model"
                        sortable
                        style="min-width: 4rem"
                    >
                        <template #body="slotProps">
                            <Button
                                v-if="slotProps.data.car"
                                link
                                :label="`${slotProps.data.car.model} - ${slotProps.data.car.color}`"
                                variant="link"
                                @click="
                                    (event) =>
                                        openCarPopover(
                                            event,
                                            slotProps.data.car,
                                        )
                                "
                            />
                            <Popover
                                ref="carPopover"
                                id="overlay_panel"
                                style="width: 250px"
                            >
                                <p class="font-bold text-xl">
                                    {{ car.model }} - {{ car.color }}
                                    {{ car.marsh }}
                                </p>
                                <br />
                                <p>
                                    <span class="font-bold">{{
                                        t("production")
                                    }}</span
                                    >: {{ car.production_year }}
                                </p>
                                <p>
                                    <span class="font-bold">{{
                                        t("target")
                                    }}</span
                                    >: {{ car.target }}
                                </p>
                                <p>
                                    <span class="font-bold">{{
                                        t("registration")
                                    }}</span
                                    >: {{ car.registration }}
                                </p>
                                <p>
                                    <span class="font-bold">{{
                                        t("technical-control")
                                    }}</span
                                    >:
                                    {{ car.technical_control }}
                                </p>
                                <p>
                                    <span class="font-bold">{{
                                        t("shasi")
                                    }}</span
                                    >:
                                    {{ car.shasi_nr }}
                                </p>
                            </Popover>
                        </template>
                    </Column>
                    <Column
                        :header="t('comment')"
                        field="comment"
                        sortable
                        style="min-width: 8rem; max-width: 12rem"
                    >
                        <template #body="slotProps">
                            <p
                                v-tooltip="slotProps.data.comment"
                                class="truncate max-w-xs"
                            >
                                {{ slotProps.data.comment }}
                            </p>
                        </template>
                    </Column>
                    <Column :exportable="false" style="min-width: 12rem">
                        <template #body="slotProps">
                            <Button
                                class="mr-2"
                                icon="pi pi-print"
                                outlined
                                rounded
                                :loading="isPrinting === slotProps.data.id"
                                @click="handlePrintRegister(slotProps.data)"
                            />
                            <Button
                                class="mr-2"
                                icon="pi pi-pencil"
                                outlined
                                rounded
                                @click="
                                    (register = { ...slotProps.data }),
                                        (registerUpdateDialog = true)
                                "
                            />

                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                @click="confirmDeleteCar(slotProps.data)"
                                severity="danger"
                            />
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>

    <!--   Create register dialog start     -->
    <CreateRegister
        v-model:visible="registerCreateDialog"
        :client="{}"
        @update:visible="registerCreateDialog = $event"
        @save="refetchRegisters"
    />
    <!--   Create register dialog end     -->

    <!--   Delete register dialog     -->
    <Dialog
        v-model:visible="deleteRegisterDialog"
        :dismissableMask="true"
        :header="t('confirm')"
        :modal="true"
        :style="{ width: '450px' }"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span v-if="register"
                >{{ t("are-sure-u-want-to-delete") }} {{ t("the-register") }}
                <br />
                {{ t("the-client") }}: {{ register.client?.full_name }}
                <br />
                {{ t("the-car") }}: {{ register.car?.color }}
                {{ register.car?.marsh }} ?</span
            >
        </div>
        <template #footer>
            <Button
                :label="t('no')"
                icon="pi pi-times"
                text
                @click="deleteRegisterDialog = false"
            />
            <Button
                :label="t('yes')"
                icon="pi pi-check"
                @click="handleDeleteRegister"
            />
        </template>
    </Dialog>
    <!--   Delete register dialog end     -->

    <!--   Update register dialog start     -->
    <Dialog
        v-model:visible="registerUpdateDialog"
        :dismissableMask="true"
        :modal="true"
        :style="{ width: '500px' }"
    >
        <template #header>
            <div class="flex justify-between items-center w-full">
                <span class="text-xl font-bold">{{
                    t("update-register")
                }}</span>
                <div class="flex items-center gap-2">
                    <LanguageSwitcher />
                </div>
            </div>
        </template>
        <Form ref="registerUpdateForm" @submit="updateRegister">
            <div class="flex flex-col">
                <Label required>
                    <span>
                        {{ t("the-client") }}
                    </span>
                </Label>

                <Select
                    v-model="register.client"
                    :options="[register.client]"
                    class="w-full"
                    :optionLabel="(client) => `${client.full_name}`"
                />
            </div>
            <div class="flex flex-col mt-4">
                <Label required>
                    <span>
                        {{ t("the-car") }}
                    </span>
                </Label>

                <Select
                    v-model="register.car"
                    :options="[register.car]"
                    class="w-full"
                    :optionLabel="
                        (car) => `${car.model} ${car.color} - ${car.marsh}`
                    "
                />
            </div>
            <div class="flex flex-col mt-6">
                <div>
                    <Label for="comment">{{ t("comment") }}</Label>
                    <Field
                        v-slot="{ values }"
                        v-model="register.comment"
                        :validateOnChange="true"
                        name="comment"
                    >
                        <Textarea
                            id="comment"
                            v-model="register.comment"
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
                    @click="(registerUpdateDialog = false), (register = {})"
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
            v-for="error in registerUpdateError.response.data.errors"
            v-if="registerUpdateError?.status === 422"
            class="mt-2 mb-2"
            icon="pi pi-times-circle"
            severity="error"
        >
            {{ error[0] }}
        </Message>
    </Dialog>
    <!--   Update register dialog end     -->
</template>
<style>
.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
