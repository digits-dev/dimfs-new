import React from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm, usePage } from '@inertiajs/react';
import DropdownSelect from '../../Components/Dropdown/Dropdown';

const MarginMatricesAction = ({action, onClose, updateData, all_active_brands, all_brands, all_active_vendor_types, all_vendor_types}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        brands_id: "" || updateData.brands_id,
        brand_description: "" || updateData.brand_description,
        margin_category: "" || updateData.margin_category,
        max: "" || updateData.max,
        min: "" || updateData.min,
        matrix_type: "" || updateData.matrix_type,
        vendor_types_id: "" || updateData.vendor_types_id,
        vendor_type_description: "" || updateData.vendor_type_description,
        store_margin_percentage: "" || updateData.store_margin_percentage,
        status: "" || updateData.status,
    });

    const statuses = [
        {
            id: 'ACTIVE',
            name:'ACTIVE',
        },
        {
            id: 'INACTIVE',
            name:'INACTIVE',
        },
    ]

    const types = [
        {
            id: 'ADD TO LC',
            name:'ADD TO LC',
        },
        {
            id: 'BASED ON MATRIX',
            name:'BASED ON MATRIX',
        },
        {
            id: 'DEDUCT FROM MALC',
            name:'DEDUCT FROM MALC',
        },
    ]

    const marginCategory = [
        {
            id: 'ACCESSORIES',
            name:'ACCESSORIES',
        },
        {
            id: 'UNIT ACCESSORIES',
            name:'UNIT ACCESSORIES',
        },
        {
            id: 'UNITS',
            name:'UNITS',
        },
    ]


    const handleFormSubmit = (e) => {
        e.preventDefault();
        Swal.fire({
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} Margin Matrix?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('margin_matrices/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["margin_matrices"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                else{
                    post('margin_matrices/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["margin_matrices"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                
            }
        });
    }

  return (
    <form onSubmit={handleFormSubmit} className='space-y-2'>
        {/* BRANDS */}
        {action == 'View' && 
            <InputComponent
                displayName="Brand"
                value={data.brand_description ?? '-'}
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
                        brand_description: selectedOption?.label
                    }))}
                    name="brand"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_brands : all_active_brands    }
                    value={data.brands_id && { label: data.brand_description, value: data.brands_id }}
                />
            )
        }
        {(errors.brand_groups_id) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.brand_groups_id}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Brand is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }

        {/* MARGIN CATEGORY */}
        {action == 'View' && 
            <InputComponent
                displayName="Margin Category"
                value={data.margin_category ?? '-'}
                disabled={action === 'View'}
                placeholder="Enter Margin Category"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <DropdownSelect
                    placeholder="Choose Margin Category"
                    selectType="react-select"
                    defaultSelect="Select Margin Category"
                    onChange={(selectedOption) => setData("margin_category", selectedOption?.value)}
                    name="margin_category"
                    options={marginCategory}
                    value={data.brands_id && { label: data.margin_category, value: data.margin_category_description }}
                />
            )
        }
        {(errors.margin_category_description) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.margin_category_description}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Margin Category is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
   

        {/* MAX */}
        <InputComponent
            name="max"
            value={data.max}
            disabled={action === 'View' || action === 'Update' && privilege != 1}
            placeholder="Enter Brand Code"
            onChange={(e)=> setData("max", e.target.value)}
        />
        {(errors.max) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.max}
            </div>
        )}

        {/* MIN */}
        <InputComponent
            name="min"
            value={data.min}
            disabled={action === 'View' || action === 'Update' && privilege != 1}
            placeholder="Enter Brand Code"
            onChange={(e)=> setData("min", e.target.value)}
        />
        {(errors.min) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.min}
            </div>
        )}

        {/* STORE MARGIN PERCENTAGE */}
        <InputComponent
            name="store_margin_percentage"
            displayName="Store Margin (%)"
            value={data.store_margin_percentage}
            disabled={action === 'View' || action === 'Update' && privilege != 1}
            placeholder="Enter Store Margin (%)"
            onChange={(e)=> setData("store_margin_percentage", e.target.value)}
        />
        {(errors.store_margin_percentage) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.store_margin_percentage}
            </div>
        )}

        {/* MATRIX TYPE */}
        {action == 'View' && 
            <InputComponent
                displayName="Type"
                value={data.matrix_type ?? '-'}
                disabled={action === 'View'}
                placeholder="Enter Matrix Type"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <DropdownSelect
                    placeholder="Choose Type"
                    selectType="react-select"
                    defaultSelect="Select Type"
                    onChange={(selectedOption) => setData("matrix_type", selectedOption?.value)}
                    name="type"
                    menuPlacement="top"
                    options={types}
                    value={data.matrix_type ? { label: data.matrix_type, value: data.matrix_type } : null}
                />
            )
        }
        {(errors.matrix_type) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.matrix_type}
            </div>
        )}

        {/* VENDOR TYPE */}
        {action == 'View' && 
            <InputComponent
                displayName="Vendor Type"
                value={data.vendor_type_description ?? '-'}
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
                        vendor_type_description: selectedOption?.label
                    }))}
                    menuPlacement="top"
                    name="vendor_type"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_vendor_types : all_active_vendor_types}
                    value={data.brands_id ? { label: data.vendor_type_description, value: data.vendor_types_id } : null}
                />
            )
        }
        {(errors.vendor_types_id) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.vendor_types_id}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Vendor Type is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }


        {action == 'Update' && 
            <>
                <DropdownSelect
                    placeholder="Choose Status"
                    selectType="react-select"
                    defaultSelect="Select Status"
                    onChange={(selectedOption) => setData("status", selectedOption?.value)}
                    name="status"
                    menuPlacement="top"
                    options={statuses}
                    value={data.status ? { label: data.status, value: data.status } : null}
                />
                {(errors.status) && (
                    <div className="font-poppins text-xs font-semibold text-red-600">
                        {errors.status}
                    </div>
                )}
            </>
        }

        {action == "View" && 
            <div className='flex items-center space-x-2'>
                <div className={`block text-sm font-bold ${theme === 'bg-skin-black' ? ' text-gray-400' : 'text-gray-700'}  font-poppins`}>
                    Status
                </div>
                <div className={`select-none ${data.status == 'ACTIVE' ? 'bg-status-success': 'bg-status-error'} mb-2 text-sm font-poppins font-semibold py-1 px-3 text-center text-white rounded-full mt-2`}>
                    {data.status}
                </div>
            </div>
        }
        
        
        {action !== 'View' && 
            <div className='flex justify-end'>
                <Button
                    type="button"
                    extendClass={`${theme === 'bg-skin-white' ? primayActiveColor : theme}`}
                    fontColor={textColorActive}
                    disabled={processing}
                >
                {processing ? 
                    (
                        action === "Add" ? 'Submitting' : 'Updating'
                    ) 
                    : 
                    (
                        <span>
                            <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i> {action === "Add" ? 'Add Margin Matrix' : 'Update Margin Matrix'}
                        </span>
                    )
                }
                </Button>  
            </div>
        }
        
    </form>
  )
}

export default MarginMatricesAction