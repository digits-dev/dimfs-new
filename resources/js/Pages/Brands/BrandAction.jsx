import React from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm } from '@inertiajs/react';
import DropdownSelect from '../../Components/Dropdown/Dropdown';

const BrandAction = ({action, onClose, updateData}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { textColor, primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        brand_code: "" || updateData.brand_code,
        brand_description: "" || updateData.brand_description,
        brand_group: "" || updateData.brand_group,
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
            disabled={action === 'View'}
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
        <InputComponent
            name="brand_group"
            value={data.brand_group}
            disabled={action === 'View'}
            placeholder="Enter Brand Group"
            onChange={(e)=> setData("brand_group", e.target.value)}
        />
        {(errors.brand_group) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.brand_group}
            </div>
        )}
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
                            <i className="fa-solid fa-plus mr-1"></i> {action === "Add" ? 'Add Brand' : 'Update Brand'}
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