import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const SubCategoriesAction = ({ action, onClose, updateData }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        categories_id: "" || updateData.categories_id,
        subcategory_code: "" || updateData.subcategory_code,
        subcategory_description: "" || updateData.subcategory_description,
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
            } Sub Category?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("sub_categories/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["sub_categories"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("sub_categories/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["sub_categories"] });
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
            {/* CATEGORIES ID */}
            <InputComponent
                name="categories_id"
                value={data.categories_id}
                disabled={action === "View"}
                placeholder="Enter Category ID"
                onChange={(e) => setData("categories_id", e.target.value)}
            />
            {errors.categories_id && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.categories_id}
                </div>
            )}
            {/* SUBCATEGORY CODE */}
            <InputComponent
                name="subcategory_code"
                value={data.subcategory_code}
                disabled={action === "View"}
                placeholder="Enter Subcategory Code"
                onChange={(e) => setData("subcategory_code", e.target.value)}
            />
            {errors.subcategory_code && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.subcategory_code}
                </div>
            )}
            {/* SUBCATEGORY DESCRIPTION */}
            <InputComponent
                name="subcategory_description"
                value={data.subcategory_description}
                disabled={action === "View"}
                placeholder="Enter Subcategory Description"
                onChange={(e) =>
                    setData("subcategory_description", e.target.value)
                }
            />
            {errors.subcategory_description && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.subcategory_description}
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
                                    ? "Add Sub Category"
                                    : "Update Sub Category"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default SubCategoriesAction;
