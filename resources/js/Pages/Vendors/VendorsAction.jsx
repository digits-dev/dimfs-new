import React from "react";
import Button from "../../Components/Table/Buttons/Button";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import InputComponent from "../../Components/Forms/Input";
import { router, useForm } from "@inertiajs/react";
import DropdownSelect from "../../Components/Dropdown/Dropdown";

const VendorsAction = ({ 
    action, 
    onClose, 
    updateData,
    all_active_brands,
    all_brands,
    all_active_vendor_types,
    all_vendor_types,
    all_active_incoterms,
    all_incoterms, }) => {
        
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        brands_id: "" || updateData.brands_id,
        brands_name: "" || updateData.brands_name,
        vendor_name: "" || updateData.vendor_name,
        vendor_types_id: "" || updateData.vendor_types_id,
        vendor_types_name: "" || updateData.vendor_types_name,
        incoterms_id: "" || updateData.incoterms_id,
        incoterms_name: "" || updateData.incoterms_name,
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
            {/* BRANDS ID  */}
            {action == 'View' && 
                <InputComponent
                    displayName="Brand"
                    value={data.brands_name}
                    disabled={action === 'View'}
                    placeholder="Enter Brand"
                />
            }
            {(action == 'Update' || action == 'Add') && 
                (   <DropdownSelect
                        placeholder="Choose Brand"
                        selectType="react-select"
                        defaultSelect="Select Brand"
                        onChange={(selectedOption) => setData((prevData) => ({
                            ...prevData,
                            brands_id: selectedOption?.value,
                            brands_name: selectedOption?.label
                        }))}
                        name="brand"
                        displayName="Brand Description"
                        isStatus={action == "Update"}
                        options={action == 'Update' ? all_brands : all_active_brands}
                        value={data.brands_id ? { label: data.brands_name, value: data.brands_id } : null}
                    />
                )
            }
            {(errors.brands_id) && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.brands_id}
                </div>
            )}
            {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Brand is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
            {/* VENDOR TYPES ID */}
            {action == 'View' && 
                <InputComponent
                    displayName="Vendor Type"
                    value={data.vendor_types_name}
                    disabled={action === 'View'}
                    placeholder="Enter Vendor Type"
                />
            }
            {(action == 'Update' || action == 'Add') && 
                (   <DropdownSelect
                        placeholder="Choose Vendor Type"
                        selectType="react-select"
                        defaultSelect="Select Vendor Type"
                        onChange={(selectedOption) => setData((prevData) => ({
                            ...prevData,
                            vendor_types_id: selectedOption?.value,
                            vendor_types_name: selectedOption?.label
                        }))}
                        name="vendor_type"
                        displayName="Vendor Type Description"
                        isStatus={action == "Update"}
                        options={action == 'Update' ? all_vendor_types : all_active_vendor_types}
                        value={data.vendor_types_id ? { label: data.vendor_types_name, value: data.vendor_types_id } : null}
                    />
                )
            }
            {(errors.vendor_types_id) && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.vendor_types_id}
                </div>
            )}
            {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Vendor Type is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }

            {/* INCOTERMS ID */}
            {action == 'View' && 
                <InputComponent
                    displayName="Incoterm Description"
                    value={data.incoterms_name}
                    disabled={action === 'View'}
                    placeholder="Enter Incoterm Description"
                />
            }
            {(action == 'Update' || action == 'Add') && 
                (   <DropdownSelect
                        placeholder="Choose Incoterm Description"
                        selectType="react-select"
                        defaultSelect="Select Incoterm Description"
                        onChange={(selectedOption) => setData((prevData) => ({
                            ...prevData,
                            incoterms_id: selectedOption?.value,
                            incoterms_name: selectedOption?.label
                        }))}
                        name="incoterms"
                        displayName="Incoterm Description"
                        isStatus={action == "Update"}
                        options={action == 'Update' ? all_incoterms : all_active_incoterms}
                        value={data.incoterms_id ? { label: data.incoterms_name, value: data.incoterms_id } : null}
                    />
                )
            }
            {(errors.incoterms_id) && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.incoterms_id}
                </div>
            )}
            {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Incoterm is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }

            {/* VENDOR NAME */}
            <InputComponent
                name="vendor_name"
                value={data.vendor_name}
                disabled={action === "View"}
                placeholder="Enter Vendor Name"
                onChange={(e) => setData("vendor_name", e.target.value.toUpperCase())}
            />
            {errors.vendor_name && (
                <div className="font-poppins text-xs font-semibold text-red-600">
                    {errors.vendor_name}
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
