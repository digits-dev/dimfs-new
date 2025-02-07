import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const SubCategoriesAction = ({ action, onClose, updateData, all_active_categories, all_categories }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        categories_id: "" || updateData.categories_id,
        category_name: "" || updateData.category_name,
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
            {/* CATEGORIES ID  */}
            {action == 'View' && 
                <InputComponent
                    name="Category Description"
                    value={data.category_name}
                    disabled={action === 'View'}
                    placeholder="Enter Class Code"
                />
            }
            {(action == 'Update' || action == 'Add') && 
                (   <DropdownSelect
                        placeholder="Choose Category"
                        selectType="react-select"
                        defaultSelect="Select Category"
                        onChange={(selectedOption) => setData((prevData) => ({
                            ...prevData,
                            categories_id: selectedOption?.value,
                            category_name: selectedOption?.label
                        }))}
                        name="category"
                        isStatus={action == "Update"}
                        options={action == 'Update' ? all_categories : all_active_categories}
                        value={data.categories_id ? { label: data.category_name, value: data.categories_id } : null}
                    />
                )
            }
            {(errors.category_name) && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.category_name}
                </div>
            )}
            {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Category is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
            {/* SUBCATEGORY CODE */}
            <InputComponent
                name="subcategory_code"
                 displayName="Sub Category Code"
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
                displayName="Sub Category Description"
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
                                <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i>{" "}
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
