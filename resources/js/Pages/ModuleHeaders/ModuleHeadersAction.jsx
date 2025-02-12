import React, { useState } from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm } from '@inertiajs/react';
import DropdownSelect from '../../Components/Dropdown/Dropdown';

const ColorsAction = ({action, onClose, updateData, all_active_modules, all_modules}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        module_id: "" || updateData.module_id,
        module_name: "" || updateData.module_name,
        name: "" || updateData.name,
        header_name: "" || updateData.header_name,
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
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} Module Header?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('module_headers/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["module_headers"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {

                        }
                    });
                }
                else{
                    post('module_headers/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["module_headers"] });
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
        {/* MODULE NAME */}
        {action == 'View' && 
            <InputComponent
                name="Module Name"
                value={data.module_name}
                disabled={action === 'View'}
                placeholder="Module Name"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <DropdownSelect
                    placeholder="Choose Module"
                    selectType="react-select"
                    defaultSelect="Select Module"
                    onChange={(selectedOption) => setData((prevData) => ({
                        ...prevData,
                        module_id: selectedOption?.value,
                        module_name: selectedOption?.label
                    }))}
                    name="module_name"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_modules : all_active_modules}
                    value={data.module_id ? { label: data.module_name, value: data.module_id } : null}
                />
            )
        }
        {(errors.module_name) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.module_name}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Module is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
        {/* HEADER NAME */}
        <InputComponent
            name="header_name"
            value={data.header_name}
            disabled={action === 'View'}
            placeholder="Enter Header Name"
            onChange={(e)=> setData("header_name", e.target.value)}
        />
        {(errors.header_name) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.header_name}
            </div>
        )}
        {/* NAME */}
        <InputComponent
            name="name"
            value={data.name}
            disabled={action === 'View'}
            placeholder="Enter Name"
            onChange={(e)=> setData("name", e.target.value)}
        />
        {(errors.name) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.name}
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
                            <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i> {action === "Add" ? 'Add Module Header' : 'Update Module Header'}
                        </span>
                    )
                }
                </Button>  
            </div>
        }
        
    </form>
  )
}

export default ColorsAction