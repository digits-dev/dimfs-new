import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm, usePage } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const VendorTypesAction = ({ action, onClose, updateData }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        vendor_type_code: "" || updateData.vendor_type_code,
        vendor_type_description: "" || updateData.vendor_type_description,
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
            } Vendor Type?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("vendor_types/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["vendor_types"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("vendor_types/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["vendor_types"] });
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
            {/* VENDOR TYPE CODE */}
            <InputComponent
                name="vendor_type_code"
                value={data.vendor_type_code}
                disabled={action === 'View' || action === 'Update' && privilege != 1}
                placeholder="Enter Vendor Type Code"
                onChange={(e) => setData("vendor_type_code", e.target.value.toUpperCase())}
            />
            {errors.vendor_type_code && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.vendor_type_code}
                </div>
            )}
            {/* VENDOR TYPE DESCRIPTION */}
            <InputComponent
                name="vendor_type_description"
                value={data.vendor_type_description}
                disabled={action === "View"}
                placeholder="Enter Vendor Type Description"
                onChange={(e) =>
                    setData("vendor_type_description", e.target.value.toUpperCase())
                }
            />
            {errors.vendor_type_description && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.vendor_type_description}
                </div>
            )}
            {action == "Update" && (
                <>
                    <DropdownSelect
                        placeholder="Choose Status"
                        selectType="react-select"
                        defaultSelect="Select Status"
                        onChange={(selectedOption) =>
                            setData("status", selectedOption?.value)
                        }
                        name="status"
                        menuPlacement="top"
                        options={statuses}
                        value={
                            data.status
                                ? { label: data.status, value: data.status }
                                : null
                        }
                    />
                    {errors.status && (
                        <div className="font-poppins text-xs font-semibold text-red-600">
                            {errors.status}
                        </div>
                    )}
                </>
            )}
            {action == "View" && (
                <div className="flex items-center space-x-2">
                    <div
                        className={`block text-sm font-bold ${
                            theme === "bg-skin-black"
                                ? " text-gray-400"
                                : "text-gray-700"
                        }  font-poppins`}
                    >
                        Status
                    </div>
                    <div
                        className={`select-none ${
                            data.status == "ACTIVE"
                                ? "bg-status-success"
                                : "bg-status-error"
                        } mb-2 text-sm font-poppins font-semibold py-1 px-3 text-center text-white rounded-full mt-2`}
                    >
                        {data.status}
                    </div>
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
                                <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i>{" "}
                                {action === "Add"
                                    ? "Add Vendor Type"
                                    : "Update Vendor Type"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default VendorTypesAction;
