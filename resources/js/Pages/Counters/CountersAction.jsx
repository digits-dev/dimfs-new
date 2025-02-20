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
        adm_module_id: "" || updateData.adm_module_id,
        module_name: "" || updateData.module_name,
        counter_code: "" || updateData.counter_code,
        code_identifier: "" || updateData.code_identifier,
        status: "" || updateData.status,
    });

    const codeIdentifiers = [
        {
            id: 'Code 1',
            name:'Code 1',
        },
        {
            id: 'Code 2',
            name:'Code 2',
        },
        {
            id: 'Code 3',
            name:'Code 3',
        },
        {
            id: 'Code 4',
            name:'Code 4',
        },
        {
            id: 'Code 5',
            name:'Code 5',
        },
        {
            id: 'Code 6',
            name:'Code 6',
        },
        {
            id: 'Code 7',
            name:'Code 7',
        },
        {
            id: 'Code 8',
            name:'Code 8',
        },
        {
            id: 'Code 9',
            name:'Code 9',
        },
    ]

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
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} Counter?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('counters/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["counters"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {

                        }
                    });
                }
                else{
                    post('counters/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["counters"] });
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
                        adm_module_id: selectedOption?.value,
                        module_name: selectedOption?.label
                    }))}
                    name="module_name"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_modules : all_active_modules}
                    value={data.adm_module_id ? { label: data.module_name, value: data.adm_module_id } : null}
                />
            )
        }
        {(errors.adm_module_id) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.adm_module_id}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the module is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
        {/* COUNTER CODE */}
        <InputComponent
            name="counter_code"
            value={data.counter_code}
            disabled={action === 'View'}
            placeholder="Enter Counter Code"
            onChange={(e)=> setData("counter_code", e.target.value)}
        />
        {(errors.counter_code) && (
            <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                {errors.counter_code}
            </div>
        )}
        {action == 'View' && 
            <InputComponent
                name="Code Identifier"
                value={data.code_identifier}
                disabled={action === 'View'}
                placeholder="Code Identifier"
            />
        }
        {/* CODE IDENTIFIER */}
        {(action == 'Update' || action == 'Add') && 
        <DropdownSelect
            placeholder="Choose Code Identifier"
            selectType="react-select"
            defaultSelect="Select Code Identifier"
            onChange={(selectedOption) => setData("code_identifier", selectedOption?.value)}
            name="code_identifier"
            menuPlacement="top"
            options={codeIdentifiers}
            value={data.code_identifier ? { label: data.code_identifier, value: data.code_identifier } : null}
        />
        }
        {(errors.code_identifier) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.code_identifier}
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
                            <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i> {action === "Add" ? 'Add Counter' : 'Update Counter'}
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