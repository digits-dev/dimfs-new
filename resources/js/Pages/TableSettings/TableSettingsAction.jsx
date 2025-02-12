import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const TableSettingsAction = ({ action, onClose, updateData }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        adm_privileges_id: "" || updateData.adm_privileges_id,
        adm_moduls_id: "" || updateData.adm_moduls_id,
        action_types_id: "" || updateData.action_types_id,
        table_name: "" || updateData.table_name,
        report_header: "" || updateData.report_header,
        report_query: "" || updateData.report_query,
        status: "" || updateData.status,
    });

    const statuses = [
        {
            id: "ACTIVE",
            name: "ACTIVE",
        },
        {
            id: "INACTIVE",
            name: "INACTIVE",
        },
    ];

    const handleFormSubmit = (e) => {
        e.preventDefault();
        Swal.fire({
            title: `<p class="font-poppins text-3xl" >Do you want ${
                action == "Add" ? "add" : "update"
            } Table Settings?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("table_settings/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["table_settings"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("table_settings/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["table_settings"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                }
            }
        });
    };

    return (
        <form onSubmit={handleFormSubmit} className="space-y-2">
            {/* ADM PRIVILEGES ID */}
            <InputComponent
                name="adm_privileges_id"
                value={data.adm_privileges_id}
                disabled={action === "View"}
                placeholder="Enter Admin Privileges ID"
                onChange={(e) => setData("adm_privileges_id", e.target.value)}
            />
            {errors.adm_privileges_id && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.adm_privileges_id}
                </div>
            )}
            {/* ADM MODULES ID */}
            <InputComponent
                name="adm_moduls_id"
                value={data.adm_moduls_id}
                disabled={action === "View"}
                placeholder="Enter Admin Modules ID"
                onChange={(e) => setData("adm_moduls_id", e.target.value)}
            />
            {errors.adm_moduls_id && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.adm_moduls_id}
                </div>
            )}
            {/* ACTION TYPES ID */}
            <InputComponent
                name="action_types_id"
                value={data.action_types_id}
                disabled={action === "View"}
                placeholder="Enter Action Types ID"
                onChange={(e) => setData("action_types_id", e.target.value)}
            />
            {errors.action_types_id && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.action_types_id}
                </div>
            )}
            {/* TABLE NAME */}
            <InputComponent
                name="table_name"
                value={data.table_name}
                disabled={action === "View"}
                placeholder="Enter Table Name"
                onChange={(e) => setData("table_name", e.target.value)}
            />
            {errors.table_name && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.table_name}
                </div>
            )}
            {/* REPORT HEADER */}
            <InputComponent
                name="report_header"
                value={data.report_header}
                disabled={action === "View"}
                placeholder="Enter Report Header"
                onChange={(e) => setData("report_header", e.target.value)}
            />
            {errors.report_header && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.report_header}
                </div>
            )}
            {/* REPORT QUERY */}
            <InputComponent
                name="report_query"
                value={data.report_query}
                disabled={action === "View"}
                placeholder="Enter Report Query"
                onChange={(e) => setData("report_query", e.target.value)}
            />
            {errors.report_query && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.report_query}
                </div>
            )}
            {action !== "View" && (
                <div className="flex justify-end">
                    <Button
                        type="button"
                        extendClass={`${
                            theme === "bg-skin-white"
                                ? primayActiveColor
                                : theme
                        }`}
                        fontColor={textColorActive}
                        disabled={processing}
                    >
                        {processing ? (
                            action === "Add" ? (
                                "Submitting"
                            ) : (
                                "Updating"
                            )
                        ) : (
                            <span>
                                <i className="fa-solid fa-plus mr-1"></i>{" "}
                                {action === "Add"
                                    ? "Add Table Settings"
                                    : "Update Table Settings"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default TableSettingsAction;
