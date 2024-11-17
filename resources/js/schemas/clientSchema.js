import * as yup from "yup";

/**
 * Create a validation schema for client data
 * @param {Function} t - Translation function for localization
 * @returns {Object} Yup validation schema
 */
const createClientSchema = (t) => {
    return yup.object({
        full_name: yup
            .string()
            .required(t("validation.required", { field: t("full_name") })),
        address: yup.string().nullable(),
        phone: yup
            .number()
            .required(t("validation.required", { field: t("phone") })),
        id_card: yup
            .string()
            .required(t("validation.required", { field: t("id_card") })),
        birth: yup.string().nullable(),
        birthplace: yup.string().nullable(),
        drivers_license_id: yup
            .string()
            .required(
                t("validation.required", { field: t("drivers-license") }),
            ),
    });
};

export default createClientSchema;
