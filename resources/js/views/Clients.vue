<script setup>
import { FilterMatchMode } from "@primevue/core/api";
import { useToast } from "primevue/usetoast";
import { computed, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { useMutation, useQuery, useQueryClient } from "@tanstack/vue-query";
import { useI18n } from "vue-i18n";
import LanguageSwitcher from "@/components/LanguageSwitcher.vue";
import * as yup from "yup";
import { ErrorMessage, Field, Form } from "vee-validate";
import { ClientService } from "@/service/api/ClientService.js";
import createClientSchema from "@/schemas/clientSchema.js";
import Label from "@/components/Label.vue";

const toast = useToast();
const queryClient = useQueryClient();

const { t, locale } = useI18n();
const route = useRoute();

const pageTitle = computed(() => t(route.meta.title));

const dt = ref();
const clientCreateDialog = ref(false);
const clientUpdateDialog = ref(false);
const deleteClientDialog = ref(false);

const client = ref({});
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const clientCreateForm = ref(null);

// list clients
const { isFetching, isError, data, error, refetch } = useQuery({
    queryKey: ["clients"],
    queryFn: async () => (await ClientService.list()).data,
});

//delete clients
const { mutate: deleteClient, isPending: isDeleting } = useMutation({
    mutationFn: async (id) => {
        await ClientService.delete(id);
    },
    onSuccess: async () => {
        const id = client.value.id;
        queryClient.setQueryData(["clients"], (oldData) => {
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
        client.value = {};
    },
    onError: (error) => {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: $("went-wrong"),
            life: 3000,
        });
        console.log(error);
        client.value = {};
    },
});

// create client
const {
    mutate: storeNewClient,
    isError: clientStoreIsError,
    error: clientStoreError,
} = useMutation({
    mutationKey: ["clients", client],
    cacheTime: 0,
    staleTime: 0,
    mutationFn: async () => {
        return await ClientService.store(client.value);
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
        client.value = {};
        clientCreateDialog.value = false;
        refetch();
    },
});

// update client
const {
    mutate: updateClient,
    isError: clientUpdateIsError,
    error: clientUpdateError,
} = useMutation({
    mutationKey: ["clients-update", client],
    cacheTime: 0,
    staleTime: 0,
    mutationFn: async () => {
        return await ClientService.update(client.value, client.value.id);
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
        client.value = {};
        clientUpdateDialog.value = false;
        refetch();
    },
});

function confirmDeleteClient(selectedCar) {
    client.value = selectedCar;
    deleteClientDialog.value = true;
}

function handleDeleteClient() {
    deleteClientDialog.value = false;
    //delete car method we get from useMutation
    deleteClient(client.value.id);
}

// open create car modal
function openNew() {
    client.value = {};
    clientCreateDialog.value = true;
}
function editClient(selectedClient) {
    client.value = { ...selectedClient };
    clientUpdateDialog.value = true;
}

function hideCreateClientDialog() {
    clientCreateDialog.value = false;
    client.value = {};
}

const schema = ref(createClientSchema(t)); // Initialize schema with the current locale

// Watching for changes in the locale to recreate the schema when translation occurs
watch(locale, () => {
    schema.value = createClientSchema(t);
});
</script>

<template>
    <div>
        <!--   List client table start     -->
        <div class="card">
            <div class="font-semibold text-xl mb-4">{{ pageTitle }}</div>

            <DataTable
                v-if="!isError"
                ref="dt"
                :currentPageReportTemplate="`${t('displaying')} {first} ${t('to')} {last} ${t('from')} {totalRecords} ${t('clients')}`"
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
                            :label="t('add-client')"
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

                <Column
                    :header="t('full_name')"
                    field="full_name"
                    sortable
                    style="min-width: 10rem"
                >
                </Column>
                <Column
                    :header="t('registers')"
                    field="registers_count"
                    sortable
                    style="min-width: 4rem; text-align: center"
                >
                    <template #body="slotProps">
                        <Chip
                            v-tooltip="t('open-registers')"
                            v-if="slotProps.data.registers_count"
                            class="text-xl cursor-pointer"
                            :label="slotProps.data.registers_count"
                        ></Chip>
                    </template>
                </Column>
                <Column
                    :header="t('address')"
                    field="address"
                    sortable
                    style="min-width: 10rem"
                />
                <Column
                    :header="t('phone')"
                    field="phone"
                    sortable
                    style="min-width: 10rem"
                />
                <Column
                    :header="t('id_card')"
                    field="id_card"
                    sortable
                    style="min-width: 10rem"
                />
                <Column
                    :header="t('birth')"
                    field="birth"
                    sortable
                    style="min-width: 8rem"
                />

                <Column
                    :header="t('drivers-license')"
                    field="drivers_license_id"
                    sortable
                    style="min-width: 10rem"
                />

                <Column :exportable="false" style="min-width: 10rem">
                    <template #body="slotProps">
                        <Button
                            v-tooltip="t('add-register')"
                            class="mr-2"
                            icon="pi pi-plus"
                            outlined
                            rounded
                            @click="addRegister(slotProps.data)"
                        />
                        <Button
                            class="mr-2"
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            @click="editClient(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteClient(slotProps.data)"
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
        <!--   List client table end     -->

        <!--   Create car dialog start     -->
        <Dialog
            v-model:visible="clientCreateDialog"
            :dismissableMask="true"
            :modal="true"
            :style="{ width: '500px' }"
        >
            <template #header>
                <div class="flex justify-between items-center w-full">
                    <span class="text-xl font-bold">{{ t("add-client") }}</span>
                    <div class="flex items-center gap-2">
                        <LanguageSwitcher />
                    </div>
                </div>
            </template>
            <Form
                ref="clientCreateForm"
                :validation-schema="schema"
                @submit="storeNewClient"
            >
                <div class="flex flex-col gap-6">
                    <div class="grid grid-cols-12">
                        <div class="col-span-8">
                            <Label required for="full_name">{{
                                t("full_name")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.full_name"
                                :validateOnChange="true"
                                name="full_name"
                            >
                                <InputText
                                    id="full_name"
                                    v-model="client.full_name"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    :placeholder="t('owner-placeholder')"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="full_name"
                                />
                            </Field>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <Label required for="id_card">{{
                                t("id_card")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.id_card"
                                :validateOnChange="true"
                                name="id_card"
                            >
                                <InputText
                                    id="id_card"
                                    v-model="client.id_card"
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
                            <Label required for="drivers_license_id">{{
                                t("drivers-license")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.drivers_license_id"
                                :validateOnChange="true"
                                name="drivers_license_id"
                            >
                                <InputText
                                    id="drivers_license_id"
                                    v-model="client.drivers_license_id"
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
                                v-model="client.address"
                                :validateOnChange="true"
                                name="address"
                            >
                                <InputText
                                    id="address"
                                    v-model="client.address"
                                    :invalid="!!errorMessage"
                                    placeholder="Hasan Prishtina"
                                    class="w-full"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="address"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <Label for="birthplace">{{
                                t("birthplace")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.birthplace"
                                :validateOnChange="true"
                                name="birthplace"
                            >
                                <InputText
                                    id="birthplace"
                                    v-model="client.birthplace"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="Ferizaj"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="birthplace"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <Label required for="registration">{{
                                t("phone")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.phone"
                                :validateOnChange="true"
                                name="phone"
                            >
                                <InputText
                                    id="phone"
                                    v-model="client.phone"
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
                                v-model="client.birth"
                                :validateOnChange="true"
                                name="birth"
                            >
                                <DatePicker
                                    v-model="client.birth"
                                    :showButtonBar="true"
                                    :showIcon="true"
                                    dateFormat="dd-mm-yy"
                                    placeholder="dd-mm-yyyy"
                                    :invalid="!!errorMessage"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="birth"
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
                        @click="hideCreateClientDialog"
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
                v-for="error in clientStoreError.response.data.errors"
                v-if="clientStoreError?.status === 422"
                class="mt-2 mb-2"
                icon="pi pi-times-circle"
                severity="error"
            >
                {{ error[0] }}
            </Message>
        </Dialog>
        <!--   Create client dialog end     -->

        <!--   Update client dialog start     -->
        <Dialog
            v-model:visible="clientUpdateDialog"
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
                ref="clientCreateForm"
                :validation-schema="schema"
                @submit="updateClient"
            >
                <div class="flex flex-col gap-6">
                    <div class="grid grid-cols-12">
                        <div class="col-span-8">
                            <Label required for="full_name">{{
                                t("full_name")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.full_name"
                                :validateOnChange="true"
                                name="full_name"
                            >
                                <InputText
                                    id="full_name"
                                    v-model="client.full_name"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    :placeholder="t('owner-placeholder')"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="full_name"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <Label required for="id_card">{{
                                t("id_card")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.id_card"
                                :validateOnChange="true"
                                name="id_card"
                            >
                                <InputText
                                    id="id_card"
                                    v-model="client.id_card"
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
                            <Label required for="drivers_license_id">{{
                                t("drivers-license")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.drivers_license_id"
                                :validateOnChange="true"
                                name="drivers_license_id"
                            >
                                <InputText
                                    id="drivers_license_id"
                                    v-model="client.drivers_license_id"
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
                                v-model="client.address"
                                :validateOnChange="true"
                                name="address"
                            >
                                <InputText
                                    id="address"
                                    v-model="client.address"
                                    :invalid="!!errorMessage"
                                    placeholder="Hasan Prishtina"
                                    class="w-full"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="address"
                                />
                            </Field>
                        </div>
                        <div class="col-span-6">
                            <Label for="birthplace">{{
                                t("birthplace")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.birthplace"
                                :validateOnChange="true"
                                name="birthplace"
                            >
                                <InputText
                                    id="birthplace"
                                    v-model="client.birthplace"
                                    :invalid="!!errorMessage"
                                    class="w-full"
                                    placeholder="Ferizaj"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="birthplace"
                                />
                            </Field>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <Label required for="registration">{{
                                t("phone")
                            }}</Label>
                            <Field
                                v-slot="{ values, errorMessage }"
                                v-model="client.phone"
                                :validateOnChange="true"
                                name="phone"
                            >
                                <InputText
                                    id="phone"
                                    v-model="client.phone"
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
                                v-model="client.birth"
                                :validateOnChange="true"
                                name="birth"
                            >
                                <DatePicker
                                    v-model="client.birth"
                                    :showButtonBar="true"
                                    :showIcon="true"
                                    dateFormat="dd-mm-yy"
                                    placeholder="dd-mm-yyyy"
                                    :invalid="!!errorMessage"
                                />
                                <ErrorMessage
                                    class="text-red-500 text-m mt-2"
                                    name="birth"
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
                        @click="(clientUpdateDialog = false), (client = {})"
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
                v-for="error in clientUpdateError.response.data.errors"
                v-if="clientUpdateError?.status === 422"
                class="mt-2 mb-2"
                icon="pi pi-times-circle"
                severity="error"
            >
                {{ error[0] }}
            </Message>
        </Dialog>
        <!--   Update client dialog end     -->

        <!--   Delete client dialog     -->
        <Dialog
            v-model:visible="deleteClientDialog"
            :dismissableMask="true"
            :header="t('confirm')"
            :modal="true"
            :style="{ width: '450px' }"
        >
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="client"
                    >{{ t("are-sure-u-want-to-delete") }}:
                    <b>{{ client.full_name }}</b
                    >?</span
                >
            </div>
            <template #footer>
                <Button
                    :label="t('no')"
                    icon="pi pi-times"
                    text
                    @click="deleteClientDialog = false"
                />
                <Button
                    :label="t('yes')"
                    icon="pi pi-check"
                    @click="handleDeleteClient"
                />
            </template>
        </Dialog>
        <!--   Delete client dialog end     -->
    </div>
</template>
