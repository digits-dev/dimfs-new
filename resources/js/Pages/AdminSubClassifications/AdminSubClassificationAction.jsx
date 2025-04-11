import React from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm, usePage } from '@inertiajs/react';
import CustomSelect from '../../Components/Dropdown/CustomSelect';

const AdminSubClassificationAction = ({action, onClose, updateData, all_active_admin_classifications, all_admin_classifications}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { auth } = usePage().props;
    const privilege  = auth.sessions.admin_privileges;

    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        sub_class_code: "" || updateData.sub_class_code,
        sub_class_description: "" || updateData.sub_class_description,
        admin_classifications_id: "" || updateData.admin_classifications_id,
        admin_classifications_name: "" || updateData.admin_classifications_name,
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
            title: `<p class="font-poppins text-3xl" >Do you want ${action == 'Add' ? 'add' : 'update'} Sub Classification?</p>`,
            showCancelButton: true,
            confirmButtonText: `${action == 'Add' ? 'Confirm' : 'Update'}`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {

                if (action == 'Add'){
                    post('admin_sub_classifications/create', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["admin_sub_classifications"] });
                            reset();
                            onClose();
                        },
                        onError: (error) => {
                        }
                    });
                }
                else{
                    post('admin_sub_classifications/update', {
                        onSuccess: (data) => {
                            const { message, type } = data.props.auth.sessions;
                            handleToast(message, type);
                            router.reload({ only: ["admin_sub_classifications"] });
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
            name="sub_class_code"
            value={data.sub_class_code}
            displayName="Sub Classification Code"
            disabled={action === 'View' || action === 'Update' && privilege != 1}
            placeholder="Enter Sub Classification Code"
            onChange={(e)=> setData("sub_class_code", e.target.value.toUpperCase())}
            onError={errors.sub_class_code}
        />
        <InputComponent
            name="sub_class_description"
            value={data.sub_class_description}
            disabled={action === 'View'}
            displayName="Sub Classification Description"
            placeholder="Enter Sub Classification Description"
            onChange={(e)=> setData("sub_class_description", e.target.value.toUpperCase())}
            onError={errors.sub_class_description}
        />
        {action == 'View' && 
            <InputComponent
                displayName="Classification"
                value={data.admin_classifications_name}
                disabled={action === 'View'}
                placeholder="Enter Classification"
            />
        }
        {(action == 'Update' || action == 'Add') && 
            (   <CustomSelect
                    placeholder="Choose Classification"
                    onChange={(selectedOption) => setData((prevData) => ({
                        ...prevData,
                        admin_classifications_id: selectedOption?.value,
                        admin_classifications_name: selectedOption?.label
                    }))}
                    name="classification"
                    menuPlacement="top"
                    isStatus={action == "Update"}
                    options={action == 'Update' ? all_admin_classifications : all_active_admin_classifications}
                    value={data.admin_classifications_id ? { label: data.admin_classifications_name, value: data.admin_classifications_id } : null}
                    onError={errors.admin_classifications_id}
                />
            )
        }
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Classification is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
      
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
                            <i className={`fa-solid ${action === "Add" ? 'fa-plus' : 'fa-pen-to-square' } mr-1`}></i> {action === "Add" ? 'Add Sub Classification' : 'Update Sub Classification'}
                        </span>
                    )
                }
                </Button>  
            </div>
        }
        
    </form>
  )
}

export default AdminSubClassificationAction