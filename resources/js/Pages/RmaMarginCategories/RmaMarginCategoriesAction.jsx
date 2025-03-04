import React, { useEffect } from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm, usePage } from '@inertiajs/react';
import DropdownSelect from '../../Components/Dropdown/Dropdown';

const RmaMarginCategoriesAction = ({action, onClose, updateData, all_active_rma_sub_classifications, all_rma_sub_classifications}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        rma_sub_classifications_id: "" || updateData.rma_sub_classifications_id,
        rma_sub_classification_name: "" || updateData.rma_sub_classification_name,
        margin_category_code: "" || updateData.margin_category_code,
        margin_category_description: "" || updateData.margin_category_description,
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


    const handleFormSubmit = (e) => {
        e.preventDefault();
        Swal.fire({
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} RMA Margin Category?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('rma_margin_categories/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["rma_margin_categories"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                else{
                    post('rma_margin_categories/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["rma_margin_categories"] });
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
        {/* SUB CLASSIFICATIONS ID  */}
        {action == 'View' && 
            <InputComponent
                name="RMA Sub Classification Description"
                value={data.rma_sub_classification_name}
                disabled={action === 'View'}
                placeholder="Enter Sub Classification"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <DropdownSelect
                    placeholder="Choose RMA Sub Classification"
                    selectType="react-select"
                    defaultSelect="Select RMA Sub Classification"
                    onChange={(selectedOption) => setData((prevData) => ({
                        ...prevData,
                        rma_sub_classifications_id: selectedOption?.value,
                        rma_sub_classification_name: selectedOption?.label
                    }))}
                    name="category"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_rma_sub_classifications : all_active_rma_sub_classifications}
                    value={data.rma_sub_classifications_id ? { label: data.rma_sub_classification_name, value: data.rma_sub_classifications_id } : null}
                />
            )
        }
        {(errors.rma_sub_classification_name) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.rma_sub_classification_name}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the category is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
        {/* MARGIN CATEGORY CODE */}
        <InputComponent
            name="margin_category_code"
            value={data.margin_category_code}
            disabled={action === 'View' || action === 'Update' && privilege != 1}
            placeholder="Enter Margin Category Code"
            onChange={(e)=> setData("margin_category_code", e.target.value.toUpperCase())}
        />
        {(errors.margin_category_code) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.margin_category_code}
            </div>
        )}
        {/* MARGIN CATEGORY DESCRIPTION */}
        <InputComponent
            name="margin_category_description"
            value={data.margin_category_description}
            disabled={action === 'View'}
            placeholder="Enter Margin Category Description"
            onChange={(e)=> setData("margin_category_description", e.target.value.toUpperCase())}
        />
        {(errors.margin_category_description) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.margin_category_description}
            </div>
        )}

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
                            <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i> {action === "Add" ? 'Add RMA Margin Category' : 'Update RMA Margin Category'}
                        </span>
                    )
                }
                </Button>  
            </div>
        }
        
    </form>
  )
}

export default RmaMarginCategoriesAction