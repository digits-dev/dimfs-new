import React from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm, usePage } from '@inertiajs/react';
import CustomSelect from '../../Components/Dropdown/CustomSelect';

const AdminSuppliersAction = ({action, onClose, updateData, all_active_admin_vendors, all_admin_vendors}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        supplier_code: "" || updateData.supplier_code,
        supplier_name: "" || updateData.supplier_name,
        admin_vendors_id: "" || updateData.admin_vendors_id,
        admin_vendors_name: "" || updateData.admin_vendors_name,
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
    ]


    const handleFormSubmit = (e) => {
        e.preventDefault();
        Swal.fire({
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} Supplier?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('admin_suppliers/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["admin_suppliers"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                else{
                    post('admin_suppliers/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["admin_suppliers"] });
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
        <InputComponent
            name="supplier_code"
            value={data.supplier_code}
            displayName="Supplier Code"
            disabled={action === 'View' || action === 'Update' && privilege != 1}
            placeholder="Enter Supplier Code"
            onChange={(e)=> setData("supplier_code", e.target.value.toUpperCase())}
            onError={errors.supplier_code}
        />
        <InputComponent
            name="supplier_name"
            value={data.supplier_name}
            disabled={action === 'View'}
            displayName="Supplier Name"
            placeholder="Enter Supplier Name"
            onChange={(e)=> setData("supplier_name", e.target.value.toUpperCase())}
            onError={errors.supplier_name}
        />
        {action == 'View' && 
            <InputComponent
                displayName="Vendor Name"
                value={data.admin_vendors_name}
                disabled={action === 'View'}
                placeholder="Enter Vendor Name"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <CustomSelect
                    placeholder="Choose Vendor Name"
                    onChange={(selectedOption) => setData((prevData) => ({
                        ...prevData,
                        admin_vendors_id: selectedOption?.value,
                        admin_vendors_name: selectedOption?.label
                    }))}
                    name="Vendor Name"
                    menuPlacement="top"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_admin_vendors : all_active_admin_vendors}
                    value={data.admin_vendors_id ? { label: data.admin_vendors_name, value: data.admin_vendors_id } : null}
                    onError={errors.admin_vendors_id}
                />
            )
        }
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Vendor Name is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
      
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
                            <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i> {action === "Add" ? 'Add Supplier' : 'Update Supplier'}
                        </span>
                    )
                }
                </Button>  
            </div>
        }
        
    </form>
  )
}

export default AdminSuppliersAction