import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const SegmentationsAction = ({ action, onClose, updateData }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        segmentation_column: "" || updateData.segmentation_column,
        segmentation_code: "" || updateData.segmentation_code,
        segmentation_description: "" || updateData.segmentation_description,
        import_header_name: "" || updateData.import_header_name,
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
            } Segmentation?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("segmentations/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["segmentations"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("segmentations/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["segmentations"] });
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
            {/* SEGMENTATION COLUMN */}
            <InputComponent
                name="segmentation_column"
                value={data.segmentation_column}
                disabled={action === "View"}
                placeholder="Enter Segmentation Column"
                onChange={(e) => setData("segmentation_column", e.target.value)}
            />
            {errors.segmentation_column && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.segmentation_column}
                </div>
            )}
            {/* SEGMENTATION CODE */}
            <InputComponent
                name="segmentation_code"
                value={data.segmentation_code}
                disabled={action === "View"}
                placeholder="Enter Segmentation Code"
                onChange={(e) => setData("segmentation_code", e.target.value)}
            />
            {errors.segmentation_code && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.segmentation_code}
                </div>
            )}
            {/* SEGMENTATION DESCRIPTION */}
            <InputComponent
                name="segmentation_description"
                value={data.segmentation_description}
                disabled={action === "View"}
                placeholder="Enter Segmentation Description"
                onChange={(e) =>
                    setData("segmentation_description", e.target.value)
                }
            />
            {errors.segmentation_description && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.segmentation_description}
                </div>
            )}
            {/* IMPORT HEADER NAME */}
            <InputComponent
                name="import_header_name"
                value={data.import_header_name}
                disabled={action === "View"}
                placeholder="Enter Import Header Name"
                onChange={(e) => setData("import_header_name", e.target.value)}
            />
            {errors.import_header_name && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.import_header_name}
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
                                <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i>{" "}
                                {action === "Add"
                                    ? "Add Segmentation"
                                    : "Update Segmentation"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default SegmentationsAction;
