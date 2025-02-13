import * as yup from "yup";

/**
 * Create a validation schema for car data
 * @param {Function} t - Translation function for localization
 * @returns {Object} Yup validation schema
 */
const createCarSchema = (t) => {
    return yup.object({
        model: yup
            .string()
            .required(t("validation.required", { field: t("model") })),
        fuel: yup
            .string()
            .required(t("validation.required", { field: t("fuel") })),
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
        technical_control: yup.string().nullable(),
        registration: yup.string().nullable(),
    });
};

export default createCarSchema;
