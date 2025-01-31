import React, { useEffect } from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm } from '@inertiajs/react';
import DropdownSelect from '../../Components/Dropdown/Dropdown';

const ClassificationsAction = ({action, onClose, updateData, all_active_categories, all_categories}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        categories_id: "" || updateData.categories_id,
        category_name: "" || updateData.category_name,
        class_code: "" || updateData.class_code,
        class_description: "" || updateData.class_description,
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
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} Classification?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('classifications/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["classifications"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                else{
                    post('classifications/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["classifications"] });
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
        {/* CATEGORIES ID  */}
        <DropdownSelect
            placeholder="Choose Category"
            selectType="react-select"
            defaultSelect="Select Category"
            onChange={(selectedOption) => setData({categories_id: selectedOption?.value, category_name: selectedOption?.label})}
            name="category"
            isStatus={action == "Update"}
            options={action == 'Update' ? all_categories : all_active_categories}
            value={data.categories_id ? { label: data.category_name, value: data.categories_id } : null}
        />
        {(errors.status) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.status}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the category is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
        {/* CLASS CODE */}
        <InputComponent
            name="class_code"
            value={data.class_code}
            disabled={action === 'View'}
            placeholder="Enter Class Code"
            onChange={(e)=> setData("class_code", e.target.value)}
        />
        {(errors.class_code) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.class_code}
            </div>
        )}
        {/* CLASS DESCRIPTION */}
        <InputComponent
            name="class_description"
            value={data.class_description}
            disabled={action === 'View'}
            placeholder="Enter Class Description"
            onChange={(e)=> setData("class_description", e.target.value)}
        />
        {(errors.class_description) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.class_description}
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
                            <i className="fa-solid fa-plus mr-1"></i> {action === "Add" ? 'Add Classification' : 'Update Classification'}
                        </span>
                    )
                }
                </Button>  
            </div>
        }
        
    </form>
  )
}

export default ClassificationsAction