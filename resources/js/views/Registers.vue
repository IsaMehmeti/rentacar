<script setup>
import { FilterMatchMode } from "@primevue/core/api";
import { useToast } from "primevue/usetoast";
import { computed, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { useMutation, useQuery, useQueryClient } from "@tanstack/vue-query";
import { useI18n } from "vue-i18n";
import { RegisterService } from "@/service/api/RegisterService.js";
import CreateRegister from "@/components/CreateRegister.vue";

const toast = useToast();
const queryClient = useQueryClient();

const { t, locale } = useI18n();
const route = useRoute();

const pageTitle = computed(() => t(route.meta.title));

const dt = ref();
const registerCreateDialog = ref(false);
const carUpdateDialog = ref(false);
const submitted = ref(false);
const deleteRegisterDialog = ref(false);

//car and client for popover
const car = ref({});
const client = ref({});

const register = ref({});
const carPopover = ref(null);
const clientPopover = ref(null);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

// list registers
const { isFetching, isError, data, error, refetch } = useQuery({
    queryKey: ["registers"],
    queryFn: async () => (await RegisterService.list()).data,
});

//delete register
const { mutate: deleteRegister, isPending: isDeleting } = useMutation({
    mutationFn: async (id) => {
        await RegisterService.delete(id);
    },
    onSuccess: async () => {
        const id = register.value.id;
        queryClient.setQueryData(["registers"], (oldData) => {
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

function openCarPopover(event, selectedCar) {
    carPopover.value.toggle(event);
    car.value = selectedCar;
}
function openClientPopover(event, selectedClient) {
    clientPopover.value.toggle(event);
    client.value = selectedClient;
}

function confirmDeleteCar(selectedRegister) {
    register.value = selectedRegister;
    deleteRegisterDialog.value = true;
}

function handleDeleteRegister() {
    deleteRegisterDialog.value = false;
    //delete car method we get from useMutation
    deleteRegister(register.value.id);
}

// handle print register
const handlePrintRegister = (selectedRegister) => {
    register.value = selectedRegister;
    printRegister();
};

// open create car modal
function createNewRegister() {
    registerCreateDialog.value = true;
}
</script>

<template>
    <div>
        <!--   List car table start     -->
        <div class="card">
            <div class="font-semibold text-xl mb-4">{{ pageTitle }}</div>

            <DataTable
                v-if="!isError"
                ref="dt"
                :currentPageReportTemplate="`${t('displaying')} {first} ${t('to')} {last} ${t('from')} {totalRecords} ${t('registers')}`"
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
                            @click="createNewRegister"
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
                    style="min-width: 3rem; text-align: center"
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
                    style="min-width: 10rem"
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
                                <span class="font-bold">{{ t("address") }}</span
                                >: {{ client.address }}
                            </p>

                            <p v-if="client.phone">
                                <span class="font-bold">{{ t("phone") }}</span
                                >: {{ client.phone }}
                            </p>
                            <p v-if="client.passaport_nr">
                                <span class="font-bold">{{
                                    t("passport_nr")
                                }}</span
                                >: {{ client.passaport_nr }}
                            </p>
                            <p v-if="client.id_card">
                                <span class="font-bold">{{ t("id_card") }}</span
                                >: {{ client.id_card }}
                            </p>
                            <p v-if="client.birth">
                                <span class="font-bold">{{ t("birth") }}</span
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
                    style="min-width: 16rem"
                >
                    <template #body="slotProps">
                        <Button
                            v-if="slotProps.data.car"
                            link
                            :label="`${slotProps.data.car.model} - ${slotProps.data.car.color} ${slotProps.data.car.marsh}`"
                            variant="link"
                            @click="
                                (event) =>
                                    openCarPopover(event, slotProps.data.car)
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
                                <span class="font-bold">{{ t("target") }}</span
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
                                <span class="font-bold">{{ t("shasi") }}</span
                                >:
                                {{ car.shasi_nr }}
                            </p>
                        </Popover>
                    </template>
                </Column>

                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            class="mr-2"
                            icon="pi pi-print"
                            outlined
                            rounded
                            @click="handlePrintRegister(slotProps.data)"
                        />
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

        <!--   Create register dialog start     -->
        <CreateRegister
            v-model:visible="registerCreateDialog"
            :client="false"
            @update:visible="registerCreateDialog = $event"
            @save="refetch"
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
                    >{{ t("are-sure-u-want-to-delete") }}
                    {{ t("the-register") }} <br />
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
    </div>
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
