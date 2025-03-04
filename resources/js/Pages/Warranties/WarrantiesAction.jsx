import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm, usePage } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const WarrantiesAction = ({ action, onClose, updateData }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        warranty_code: "" || updateData.warranty_code,
        warranty_description: "" || updateData.warranty_description,
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
            } Warranty?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("warranties/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["warranties"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("warranties/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["warranties"] });
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
            {/* WARRANTY CODE */}
            <InputComponent
                name="warranty_code"
                value={data.warranty_code}
                disabled={action === 'View' || action === 'Update' && privilege != 1}
                placeholder="Enter Warranty Code"
                onChange={(e) => setData("warranty_code", e.target.value.toUpperCase())}
            />
            {errors.warranty_code && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.warranty_code}
                </div>
            )}
            {/* WARRANTY DESCRIPTION */}
            <InputComponent
                name="warranty_description"
                value={data.warranty_description}
                disabled={action === "View"}
                placeholder="Enter Warranty Description"
                onChange={(e) =>
                    setData("warranty_description", e.target.value.toUpperCase())
                }
            />
            {errors.warranty_description && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.warranty_description}
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
                                <i className="fa-solid fa-plus mr-1"></i>{" "}
                                {action === "Add"
                                    ? "Add Warranty"
                                    : "Update Warranty"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default WarrantiesAction;
