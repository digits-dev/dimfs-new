import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm, usePage } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const SubClassificationsAction = ({ action, onClose, updateData, all_classifications, all_active_classifications }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        classifications_id: "" || updateData.classifications_id,
        classification_name: "" || updateData.classification_name,
        subclass_code: "" || updateData.subclass_code,
        subclass_description: "" || updateData.subclass_description,
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
            } Sub Classification?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("sub_classifications/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["sub_classifications"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("sub_classifications/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["sub_classifications"] });
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
            {/* CLASSIFICATIONS ID  */}
            {action == 'View' && 
                <InputComponent
                    name="Sub Classification Description"
                    value={data.classification_name}
                    disabled={action === 'View'}
                    placeholder="Enter Classification Name"
                />
            }
            {(action == 'Update' || action == 'Add') && 
                (   <DropdownSelect
                        placeholder="Choose Classification"
                        selectType="react-select"
                        defaultSelect="Select Category"
                        onChange={(selectedOption) => setData((prevData) => ({
                            ...prevData,
                            classifications_id: selectedOption?.value,
                            classification_name: selectedOption?.label
                        }))}
                        name="classification_description"
                        isStatus={action == "Update"}
                        options={action == 'Update' ? all_classifications : all_active_classifications}
                        value={data.classifications_id ? { label: data.classification_name, value: data.classifications_id } : null}
                    />
                )
            }
            {(errors.classification_name) && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.classification_name}
                </div>
            )}
            {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Classification is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
            {/* SUBCLASS CODE */}
            <InputComponent
                name="subclass_code"
                value={data.subclass_code}
                disabled={action === 'View' || action === 'Update' && privilege != 1}
                placeholder="Enter Subclass Code"
                onChange={(e) => setData("subclass_code", e.target.value)}
            />
            {errors.subclass_code && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.subclass_code}
                </div>
            )}
            {/* SUBCLASS DESCRIPTION */}
            <InputComponent
                name="subclass_description"
                value={data.subclass_description}
                disabled={action === "View"}
                placeholder="Enter Subclass Description"
                onChange={(e) =>
                    setData("subclass_description", e.target.value)
                }
            />
            {errors.subclass_description && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.subclass_description}
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
                                    ? "Add Sub Classification"
                                    : "Update Sub Classification"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default SubClassificationsAction;
