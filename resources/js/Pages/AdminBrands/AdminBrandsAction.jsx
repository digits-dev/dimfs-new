import React from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm, usePage } from '@inertiajs/react';
import CustomSelect from '../../Components/Dropdown/CustomSelect';

const AdminBrandsAction = ({action, onClose, updateData, all_active_brand_types, all_brand_types}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        brand_code: "" || updateData.brand_code,
        brand_description: "" || updateData.brand_description,
        admin_brand_types_id: "" || updateData.admin_brand_types_id,
        admin_brand_types_name: "" || updateData.admin_brand_types_name,
        status: "" || updateData.status,
    });

    const statuses = [
        {
            value: 'ACTIVE',
            label:'ACTIVE',
        },
        {
            value: 'INACTIVE',
            label:'INACTIVE',
        },
        {
            value: 'STATUS QUO',
            label:'STATUS QUO',
        },
        {
            value: 'CORE',
            label:'CORE',
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
                    post('admin_brands/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["admin_brands"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                else{
                    post('admin_brands/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["admin_brands"] });
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
            onChange={(e)=> setData("brand_code", e.target.value.toUpperCase())}
            onError={errors.brand_code}
        />
        {/* BRAND DESCRIPTION */}
        <InputComponent
            name="brand_description"
            value={data.brand_description}
            disabled={action === 'View'}
            placeholder="Enter Brand Description"
            onChange={(e)=> setData("brand_description", e.target.value.toUpperCase())}
            onError={errors.brand_description}
        />
        {/* BRAND BEA CODE */}
        <InputComponent
            name="brand_beacode"
            displayName="Brand BEA Code"
            value={data.contact_name}
            disabled={action === 'View'}
            placeholder="Enter Brand BEA Code"
            onChange={(e)=> setData("brand_beacode", e.target.value.toUpperCase())}
            onError={errors.brand_beacode}
        />
        {/* ADMIN BRAND TYPES */}
        {action == 'View' && 
            <InputComponent
                displayName="Brand Type"
                value={data.admin_brand_types_name}
                disabled={action === 'View'}
                placeholder="Enter Brand Type"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <CustomSelect
                    placeholder="Choose Brand Type"
                    onChange={(selectedOption) => setData((prevData) => ({
                        ...prevData,
                        admin_brand_types_id: selectedOption?.value,
                        admin_brand_types_name: selectedOption?.label
                    }))}
                    name="brand_type"
                    menuPlacement="top"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_brand_types : all_active_brand_types}
                    value={data.admin_brand_types_id ? { label: data.admin_brand_types_name, value: data.admin_brand_types_id } : null}
                    onError={errors.admin_brand_types_id}
                />
            )
        }
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Brand Type is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
      
        {action == 'Update' && 
            <CustomSelect
                placeholder="Choose Status"
                selectType="react-select"
                defaultSelect="Select Status"
                onChange={(selectedOption) => setData("status", selectedOption?.value)}
                name="status"
                menuPlacement="top"
                onError={errors.status}
                options={statuses}
                value={data.status ? { label: data.status, value: data.status } : null}
            />
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

export default AdminBrandsAction