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
        cms_modules_id: "" || updateData.cms_modules_id,
        module_name: "" || updateData.module_name,
        code_1: "" || updateData.code_1,
        code_2: "" || updateData.code_2,
        code_3: "" || updateData.code_3,
        code_4: "" || updateData.code_4,
        code_5: "" || updateData.code_5,
        code_6: "" || updateData.code_6,
        code_7: "" || updateData.code_7,
        code_8: "" || updateData.code_8,
        code_9: "" || updateData.code_9,
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
                        cms_modules_id: selectedOption?.value,
                        module_name: selectedOption?.label
                    }))}
                    name="module_name"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_modules : all_active_modules}
                    value={data.cms_modules_id ? { label: data.module_name, value: data.cms_modules_id } : null}
                />
            )
        }
        {(errors.module_name) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.module_name}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the module is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
        <div className='flex space-x-3'>
            <div className='flex-1'>
                {/* CODE 1 */}
                <InputComponent
                    name="code_1"
                    value={data.code_1}
                    disabled={action === 'View'}
                    placeholder="Enter Code 1"
                    onChange={(e)=> setData("code_1", e.target.value)}
                />
                {(errors.code_1) && (
                    <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                        {errors.code_1}
                    </div>
                )}
            </div>
            <div className='flex-1'>
            {/* CODE 2 */}
                <InputComponent
                    name="code_2"
                    value={data.code_2}
                    disabled={action === 'View'}
                    placeholder="Enter Code 2"
                    onChange={(e)=> setData("code_2", e.target.value)}
                />
                {(errors.code_2) && (
                    <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                        {errors.code_2}
                    </div>
                )}
            </div>
        </div>
        <div className='flex space-x-3'>
            <div className='flex-1'>
                {/* CODE 3 */}
                <InputComponent
                    name="code_3"
                    value={data.code_3}
                    disabled={action === 'View'}
                    placeholder="Enter Code 3"
                    onChange={(e)=> setData("code_3", e.target.value)}
                />
                {(errors.code_3) && (
                    <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                        {errors.code_3}
                    </div>
                )}
            </div>
            <div className='flex-1'>
                {/* CODE 4 */}
                <InputComponent
                    name="code_4"
                    value={data.code_4}
                    disabled={action === 'View'}
                    placeholder="Enter Code 4"
                    onChange={(e)=> setData("code_4", e.target.value)}
                />
                {(errors.code_4) && (
                    <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                        {errors.code_4}
                    </div>
                )}
            </div>
        </div>
        <div className='flex space-x-3'>
            <div className='flex-1'>
                {/* CODE 5 */}
                <InputComponent
                    name="code_5"
                    value={data.code_5}
                    disabled={action === 'View'}
                    placeholder="Enter Code 5"
                    onChange={(e)=> setData("code_5", e.target.value)}
                />
                {(errors.code_5) && (
                    <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                        {errors.code_5}
                    </div>
                )}
            </div>
            <div className='flex-1'>
                {/* CODE 6 */}
                <InputComponent
                    name="code_6"
                    value={data.code_6}
                    disabled={action === 'View'}
                    placeholder="Enter Code 6"
                    onChange={(e)=> setData("code_6", e.target.value)}
                />
                {(errors.code_6) && (
                    <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                        {errors.code_6}
                    </div>
                )}
            </div>
        </div>
        <div className='flex space-x-3'>
            <div className='flex-1'>
                {/* CODE 7 */}
                <InputComponent
                    name="code_7"
                    value={data.code_7}
                    disabled={action === 'View'}
                    placeholder="Enter Code 7"
                    onChange={(e)=> setData("code_7", e.target.value)}
                />
                {(errors.code_7) && (
                    <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                        {errors.code_7}
                    </div>
                )}
            </div>
            <div className='flex-1'>
                {/* CODE 8 */}
                <InputComponent
                    name="code_8"
                    value={data.code_8}
                    disabled={action === 'View'}
                    placeholder="Enter Code 8"
                    onChange={(e)=> setData("code_8", e.target.value)}
                />
                {(errors.code_8) && (
                    <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                        {errors.code_8}
                    </div>
                )}
            </div>
        </div>
        {/* CODE 9 */}
        <InputComponent
            name="code_9"
            value={data.code_9}
            disabled={action === 'View'}
            placeholder="Enter Code 9"
            onChange={(e)=> setData("code_9", e.target.value)}
        />
        {(errors.code_9) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.code_9}
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
                            <i className="fa-solid fa-plus mr-1"></i> {action === "Add" ? 'Add Counter' : 'Update Counter'}
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