<script setup>
import LanguageSwitcher from "@/components/LanguageSwitcher.vue";
import { useI18n } from "vue-i18n";
import { onMounted, ref, watch } from "vue";
import { useMutation, useQuery } from "@tanstack/vue-query";
import moment from "moment";
import { CarService } from "@/service/api/CarService";
import { HomeService } from "@/service/api/HomeService";

const { t, locale } = useI18n();

const props = defineProps({
    visible: Boolean,
    car: Object,
});

const formatCurrency = (value) => {
    return value?.toLocaleString(locale.value, {
        style: "currency",
        currency: "EUR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });
};
const allDisabledDates = ref([]);
const carRegisters = ref(undefined);
const emit = defineEmits(["update:visible"]);
const internalVisible = ref(false);

const getDateClass = (dateObj) => {
    const currentDate = new Date(dateObj.year, dateObj.month, dateObj.day);
    currentDate.setHours(0, 0, 0, 0); // Normalize the time for comparison

    if (!carRegisters.value) {
        return "";
    }

    for (const register of carRegisters.value) {
        const startDate = moment(register.start_date, "DD-MM-YYYY").toDate();
        const endDate = moment(register.end_date, "DD-MM-YYYY").toDate();
        startDate.setHours(0, 0, 0, 0);
        endDate.setHours(0, 0, 0, 0);

        if (currentDate.getTime() === startDate.getTime()) {
            return "p-datepicker-day p-datepicker-day-selected"; // Class for the start date
        } else if (currentDate.getTime() === endDate.getTime()) {
            return "p-datepicker-day p-datepicker-day-selected"; // Class for the end date
        } else if (currentDate > startDate && currentDate < endDate) {
            return "p-datepicker-day p-datepicker-day-selected-range"; // Class for dates between start and end
        }
    }
    return ""; // Default class if no conditions are met
};

// list car registers
const {
    mutate: getCarRegisters,
    isLoading: loadingCarRegisters,
    data: carRegistersData,
    isError: isErrorCarRegisters,
    error: errorCarRegisters,
} = useMutation({
    queryKey: ["cars-registers"],
    mutationFn: async (car) => {
        allDisabledDates.value = [];
        const data = await CarService.registers(car.id);
        data.data.forEach((register) => {
            const start = moment(register.start_date, "DD-MM-YYYY").toDate();
            const end = moment(register.end_date, "DD-MM-YYYY").toDate();

            let currentDate = new Date(start.getTime());
            while (currentDate <= end) {
                allDisabledDates.value.push(new Date(currentDate));
                currentDate.setDate(currentDate.getDate() + 1);
            }
        });
        carRegisters.value = data.data;
        return data.data;
    },
});

// list Car Home
const {
    isPending: isCarDataPending,
    mutate: getCarData,
    data: carData,
    refetch: refetchHome,
} = useMutation({
    queryKey: ["home"],
    mutationFn: async (car) => (await HomeService.byCar(car.id)).data,
});

const handleClose = () => {
    internalVisible.value = false;
};

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

watch(
    () => props.visible,
    (newVal) => {
        internalVisible.value = newVal;
        if (newVal) {
            getCarRegisters(props.car);
            getCarData(props.car);
        }
    },
);
</script>

<template>
    <Dialog
        :visible="internalVisible"
        :dismissableMask="true"
        :modal="true"
        :style="{ width: '700px' }"
        @update:visible="handleClose"
    >
        <div
            v-if="loadingCarRegisters || isCarDataPending"
            class="spinner-overlay"
        >
            <ProgressSpinner
                style="width: 50px; height: 50px"
                strokeWidth="8"
                fill="transparent"
                animationDuration=".5s"
                aria-label="Custom ProgressSpinner"
            />
        </div>

        <template #header>
            <div class="flex justify-between items-center w-full">
                <span class="text-xl font-bold"
                    >{{ car.model }} {{ car.color }} -
                    {{ car.production_year }}</span
                >
                <div class="flex items-center gap-2">
                    <LanguageSwitcher />
                </div>
            </div>
        </template>
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
                                {{ carData?.data?.counts?.total }}
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
                                {{ carData?.data?.counts?.today }}
                            </span>
                            <br />
                            <span class="text-lg text-muted-color"
                                >{{ t("this-month") }}:
                            </span>
                            <span
                                class="text-lg text-primary font-medium"
                                :class="{ 'blur-md': registersHidden }"
                            >
                                {{ carData?.data?.counts?.monthly }}
                            </span>
                            <br />
                            <span class="text-lg text-muted-color"
                                >{{ t("this-year") }}:
                            </span>
                            <span
                                class="text-lg text-primary font-medium"
                                :class="{ 'blur-md': registersHidden }"
                            >
                                {{ carData?.data?.counts?.yearly }}
                            </span>
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
                                {{ formatCurrency(carData?.data?.total) }}
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-center bg-orange-100 dark:bg-orange-400/10 rounded-border"
                            style="width: 4.5rem; height: 4.5rem"
                        >
                            <i
                                class="pi pi-dollar text-orange-500 !text-3xl"
                            ></i>
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
                                {{ formatCurrency(carData?.data?.today) }}
                            </span>
                            <br />
                            <span class="text-lg text-muted-color"
                                >{{ t("this-month") }}:
                            </span>
                            <span
                                class="text-lg text-primary font-medium"
                                :class="{ 'blur-md': revenueHidden }"
                            >
                                {{ formatCurrency(carData?.data?.monthly) }}
                            </span>
                            <br />
                            <span class="text-lg text-muted-color"
                                >{{ t("this-year") }}:</span
                            >
                            <span
                                class="text-lg text-primary font-medium"
                                :class="{ 'blur-md': revenueHidden }"
                            >
                                {{ formatCurrency(carData?.data?.yearly) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12">
                <div class="col-span-10">
                    <DatePicker
                        inline
                        :manualInput="false"
                        :showButtonBar="true"
                        :showIcon="true"
                        dateFormat="dd-mm-yy"
                        class="w-full"
                        :firstDayOfWeek="1"
                    >
                        <template #date="{ date }">
                            <span :class="getDateClass(date)">
                                {{ date.day }}
                            </span>
                        </template>
                    </DatePicker>
                </div>
            </div>
        </div>
        <template #footer>
            <Button
                :label="t('close')"
                icon="pi pi-times"
                text
                @click="handleClose"
            />
        </template>
        <!-- Add a clear div -->
        <div style="clear: both"></div>
    </Dialog>
</template>

<style scoped>
.disabled {
    text-decoration: line-through;
}
.loading-overlay {
    position: relative;
    filter: blur(2px);
    opacity: 0.6;
    pointer-events: none; /* Disable interaction with the content while loading */
}

.spinner-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
}
</style>
