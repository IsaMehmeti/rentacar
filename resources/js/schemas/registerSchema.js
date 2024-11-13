import * as yup from "yup";

/**
 * Create a validation schema for register data
 * @param {Function} t - Translation function for localization
 * @returns {Object} Yup validation schema
 */
const createRegisterSchema = (t) => {
    return yup.object({
        price_per_day: yup
            .number()
            .required(t("validation.required", { field: t("price_per_day") })),
        dates: yup
            .array()
            .required(t("validation.required", { field: t("dates") })),
        // end_date: yup
        //     .date()
        //     .required(t("validation.required", { field: t("end_date") })),
        car: yup
            .object()
            .required(t("validation.required", { field: t("the-car") })),

        total_price: yup
            .number()
            .required(t("validation.required", { field: t("total_price") })),

        // comment: yup.string().nullable(),
        //
        // fuel_status: yup.string().nullable(),
        // driver: yup.string().nullable(),
    });
};
export default createRegisterSchema;
