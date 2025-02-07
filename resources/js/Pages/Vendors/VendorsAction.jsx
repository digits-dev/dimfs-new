import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const VendorsAction = ({ action, onClose, updateData }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        brands_id: "" || updateData.brands_id,
        vendor_name: "" || updateData.vendor_name,
        vendor_types_id: "" || updateData.vendor_types_id,
        incoterms_id: "" || updateData.incoterms_id,
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
            } Vendor?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("vendors/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["vendors"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("vendors/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["vendors"] });
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
            {/* BRANDS ID */}
            <InputComponent
                name="brands_id"
                value={data.brands_id}
                disabled={action === "View"}
                placeholder="Enter Brand ID"
                onChange={(e) => setData("brands_id", e.target.value)}
            />
            {errors.brands_id && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.brands_id}
                </div>
            )}
            {/* VENDOR NAME */}
            <InputComponent
                name="vendor_name"
                value={data.vendor_name}
                disabled={action === "View"}
                placeholder="Enter Vendor Name"
                onChange={(e) => setData("vendor_name", e.target.value)}
            />
            {errors.vendor_name && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.vendor_name}
                </div>
            )}
            {/* VENDOR TYPES ID */}
            <InputComponent
                name="vendor_types_id"
                value={data.vendor_types_id}
                disabled={action === "View"}
                placeholder="Enter Vendor Type ID"
                onChange={(e) => setData("vendor_types_id", e.target.value)}
            />
            {errors.vendor_types_id && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.vendor_types_id}
                </div>
            )}
            {/* INCOTERMS ID */}
            <InputComponent
                name="incoterms_id"
                value={data.incoterms_id}
                disabled={action === "View"}
                placeholder="Enter Incoterms ID"
                onChange={(e) => setData("incoterms_id", e.target.value)}
            />
            {errors.incoterms_id && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.incoterms_id}
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
                                <i className="fa-solid fa-plus mr-1"></i>{" "}
                                {action === "Add"
                                    ? "Add Vendor"
                                    : "Update Vendor"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default VendorsAction;
