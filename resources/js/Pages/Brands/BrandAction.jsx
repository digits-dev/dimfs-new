import React from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm, usePage } from '@inertiajs/react';
import DropdownSelect from '../../Components/Dropdown/Dropdown';

const BrandAction = ({action, onClose, updateData, all_active_brand_groups, all_brand_groups}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        brand_code: "" || updateData.brand_code,
        brand_description: "" || updateData.brand_description,
        brand_groups_id: "" || updateData.brand_groups_id,
        brand_groups_name: "" || updateData.brand_groups_name,
        contact_name: "" || updateData.contact_name,
        contact_email: "" || updateData.contact_email,
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
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} Brand?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('brands/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["brands"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                else{
                    post('brands/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["brands"] });
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
        {/* BRAND CODE */}
        <InputComponent
            name="brand_code"
            value={data.brand_code}
            disabled={action === 'View' || action === 'Update' && privilege != 1}
            placeholder="Enter Brand Code"
            onChange={(e)=> setData("brand_code", e.target.value)}
        />
        {(errors.brand_code) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.brand_code}
            </div>
        )}
        {/* BRAND DESCRIPTION */}
        <InputComponent
            name="brand_description"
            value={data.brand_description}
            disabled={action === 'View'}
            placeholder="Enter Brand Description"
            onChange={(e)=> setData("brand_description", e.target.value)}
        />
        {(errors.brand_description) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.brand_description}
            </div>
        )}
        {/* BRAND GROUP */}
        {action == 'View' && 
            <InputComponent
                displayName="Brand Group"
                value={data.category_name}
                disabled={action === 'View'}
                placeholder="Enter Brand Group"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <DropdownSelect
                    placeholder="Choose Brand Group"
                    selectType="react-select"
                    defaultSelect="Select Brand Group"
                    onChange={(selectedOption) => setData((prevData) => ({
                        ...prevData,
                        brand_groups_id: selectedOption?.value,
                        brand_groups_name: selectedOption?.label
                    }))}
                    name="brand_group"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_brand_groups : all_active_brand_groups}
                    value={data.brand_groups_id ? { label: data.brand_groups_name, value: data.brand_groups_id } : null}
                />
            )
        }
        {(errors.brand_groups_id) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.brand_groups_id}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Brand Group is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
        {/* CONTACT NAME */}
        <InputComponent
            name="contact_name"
            value={data.contact_name}
            disabled={action === 'View'}
            placeholder="Enter Contact Name"
            onChange={(e)=> setData("contact_name", e.target.value)}
        />
        {(errors.contact_name) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.contact_name}
            </div>
        )}
        {/* CONTACT EMAIL */}
        <InputComponent
            name="contact_email"
            value={data.contact_email}
            disabled={action === 'View'}
            placeholder="Enter Contact Email"
            onChange={(e)=> setData("contact_email", e.target.value)}
        />
        {(errors.contact_email) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.contact_email}
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
                            <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i> {action === "Add" ? 'Add Brand' : 'Update Brand'}
                        </span>
                    )
                }
                </Button>  
            </div>
        }
        
    </form>
  )
}

export default BrandAction