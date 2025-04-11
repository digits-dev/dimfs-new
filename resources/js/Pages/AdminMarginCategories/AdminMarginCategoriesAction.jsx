import React from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm, usePage } from '@inertiajs/react';
import CustomSelect from '../../Components/Dropdown/CustomSelect';

const AdminMarginCategoriesAction = ({action, onClose, updateData, all_active_admin_sub_classifications, all_admin_sub_classifications}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        margin_category_code: "" || updateData.margin_category_code,
        margin_category_description: "" || updateData.margin_category_description,
        admin_sub_classifications_id: "" || updateData.admin_sub_classifications_id,
        admin_sub_classifications_name: "" || updateData.admin_sub_classifications_name,
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
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} Margin Category?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('admin_margin_categories/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["admin_margin_categories"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                else{
                    post('admin_margin_categories/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["admin_margin_categories"] });
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
            name="margin_category_code"
            value={data.margin_category_code}
            displayName="Margin Category Code"
            disabled={action === 'View' || action === 'Update' && privilege != 1}
            placeholder="Enter Margin Category Code"
            onChange={(e)=> setData("margin_category_code", e.target.value.toUpperCase())}
            onError={errors.margin_category_code}
        />
        <InputComponent
            name="margin_category_description"
            value={data.margin_category_description}
            disabled={action === 'View'}
            displayName="Margin Category Description"
            placeholder="Enter Margin Category Description"
            onChange={(e)=> setData("margin_category_description", e.target.value.toUpperCase())}
            onError={errors.margin_category_description}
        />
        {action == 'View' && 
            <InputComponent
                displayName="Sub Margin Category Description"
                value={data.admin_sub_classifications_name}
                disabled={action === 'View'}
                placeholder="Enter Sub Classification Description"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <CustomSelect
                    placeholder="Choose Sub Classification"
                    onChange={(selectedOption) => setData((prevData) => ({
                        ...prevData,
                        admin_sub_classifications_id: selectedOption?.value,
                        admin_sub_classifications_name: selectedOption?.label
                    }))}
                    name="Sub Classification"
                    menuPlacement="top"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_admin_sub_classifications : all_active_admin_sub_classifications}
                    value={data.admin_sub_classifications_id ? { label: data.admin_sub_classifications_name, value: data.admin_sub_classifications_id } : null}
                    onError={errors.admin_sub_classifications_id}
                />
            )
        }
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Category is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
      
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
                            <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i> {action === "Add" ? 'Add Margin Category' : 'Update Margin Category'}
                        </span>
                    )
                }
                </Button>  
            </div>
        }
        
    </form>
  )
}

export default AdminMarginCategoriesAction