import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const ModelSpecificsAction = ({ action, onClose, updateData }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        model_specific_code: "" || updateData.model_specific_code,
        model_specific_description: "" || updateData.model_specific_description,
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
            } Model Specific?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("model_specifics/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["model_specifics"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("model_specifics/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["model_specifics"] });
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
            {/* MODEL SPECIFIC CODE */}
            <InputComponent
                name="model_specific_code"
                value={data.model_specific_code}
                disabled={action === "View"}
                placeholder="Enter Model Specific Code"
                onChange={(e) => setData("model_specific_code", e.target.value)}
            />
            {errors.model_specific_code && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.model_specific_code}
                </div>
            )}
            {/* MODEL SPECIFIC DESCRIPTION */}
            <InputComponent
                name="model_specific_description"
                value={data.model_specific_description}
                disabled={action === "View"}
                placeholder="Enter Model Specific Description"
                onChange={(e) =>
                    setData("model_specific_description", e.target.value)
                }
            />
            {errors.model_specific_description && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.model_specific_description}
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
                                    ? "Add Model Specific"
                                    : "Update Model Specific"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default ModelSpecificsAction;
