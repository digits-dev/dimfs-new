import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const VendorGroupsAction = ({ action, onClose, updateData, all_active_vendors, all_vendors }) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        vendors_id: "" || updateData.vendors_id,
        vendors_name: "" || updateData.vendors_name,
        vendor_group_name: "" || updateData.vendor_group_name,
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
            } Vendor Group?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == "Add" ? "Confirm" : "Update"}`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                if (action == "Add") {
                    post("vendor_groups/create", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["vendor_groups"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {},
                    });
                } else {
                    post("vendor_groups/update", {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["vendor_groups"] });
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
            {/* VENDORS ID  */}
            {action == 'View' && 
                <InputComponent
                    name="Vendor Name"
                    value={data.vendors_name}
                    disabled={action === 'View'}
                    placeholder="Enter Vendor Name"
                />
            }
            {(action == 'Update' || action == 'Add') && 
                (   <DropdownSelect
                        placeholder="Choose Vendor Name"
                        selectType="react-select"
                        defaultSelect="Select Vendor Name"
                        onChange={(selectedOption) => setData((prevData) => ({
                            ...prevData,
                            vendors_id: selectedOption?.value,
                            vendors_name: selectedOption?.label
                        }))}
                        name="vendor_name"
                        isStatus={action == "Update"}
                        options={action == 'Update' ? all_vendors : all_active_vendors}
                        value={data.vendors_id ? { label: data.vendors_name, value: data.vendors_id } : null}
                    />
                )
            }
            {(errors.vendors_name) && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.vendors_name}
                </div>
            )}
            {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Vendor Name is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
            {/* VENDOR GROUP NAME */}
            <InputComponent
                name="vendor_group_name"
                value={data.vendor_group_name}
                disabled={action === "View"}
                placeholder="Enter Vendor Group Name"
                onChange={(e) => setData("vendor_group_name", e.target.value)}
            />
            {errors.vendor_group_name && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.vendor_group_name}
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
                                    ? "Add Vendor Group"
                                    : "Update Vendor Group"}
                            </span>
                        )}
                    </Button>
                </div>
            )}
        </form>
    );
};

export default VendorGroupsAction;
