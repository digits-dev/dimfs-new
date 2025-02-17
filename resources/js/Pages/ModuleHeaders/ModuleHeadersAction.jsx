import React, { useEffect, useState } from 'react'
import Button from '../../Components/Table/Buttons/Button';
import { useTheme } from '../../Context/ThemeContext';
import { useToast } from '../../Context/ToastContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import InputComponent from '../../Components/Forms/Input';
import { router, useForm } from '@inertiajs/react';
import DropdownSelect from '../../Components/Dropdown/Dropdown';

const ColorsAction = ({action, onClose, updateData, all_active_modules, all_modules, item_master_columns, gashapon_item_master_columns, database_tables_and_columns}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);
    const [columns, setColumns] = useState([]);

    

    useEffect(()=>{

        console.log(columns);
        
    })

    const { data, setData, processing, reset, post, errors } = useForm({
        id: "" || updateData.id,
        module_id: "" || updateData.module_id,
        module_name: "" || updateData.module_name,
        name: "" || updateData.name,
        header_name: "" || updateData.header_name,
        width: "" || updateData.width,
        type: "" || updateData.type,
        table: "" || updateData.table,
        table_join: "" || updateData.table_join,
        table_select_value: "" || updateData.table_select_value,
        table_select_label: "" || updateData.table_select_label,
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

    const widths = [
        {
            id: 'auto',
            name:'auto',
        },
        {
            id: 'sm',
            name:'sm',
        },
        {
            id: 'md',
            name:'md',
        },
        {
            id: 'lg',
            name:'lg',
        },
        {
            id: 'xl',
            name:'xl',
        },
        {
            id: '2xl',
            name:'2xl',
        },
    ]

    const InputTypes = [
        {
            id: 'text',
            name:'Text',
        },
        {
            id: 'select',
            name:'Select',
        },
        {
            id: 'date',
            name:'Date',
        },
    ]

    const handleTableChange = (selectedOption) => {
        setData("table", selectedOption?.value)
        const selectedTableData = database_tables_and_columns.find(table => table.table_name === selectedOption.value);
        setColumns(selectedTableData ? selectedTableData.columns.map(column => ({
            id: column,
            name: column,
        })) : []);
    };

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
                    placeholder="Select Module"
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
        {/* NAME */}
        <DropdownSelect
            placeholder="Select Column Name"
            selectType="react-select"
            defaultSelect="Select Column Name"
            isDisabled={!data.module_id}
            onChange={(selectedOption) => setData("name", selectedOption?.value)}
            name="column_name"
            options={data.module_name == 'Item Master' ? item_master_columns : gashapon_item_master_columns}
            value={data.name ? { label: data.name, value: data.name } : null}
        />
        {(errors.name) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.name}
            </div>
        )}
        {action == 'Update' && <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Module is in red text, it means it is <span className='text-red-500'>INACTIVE</span>.</div> }
        {/* HEADER NAME */}
        <InputComponent
            name="header_name"
            value={data.header_name}
            disabled={action === 'View'}
            placeholder="Enter Header Name (ex. Header Name)"
            onChange={(e)=> setData("header_name", e.target.value)}
        />
        {(errors.header_name) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.header_name}
            </div>
        )}
        {/* WIDTH */}
        <DropdownSelect
            placeholder="Choose Width"
            selectType="react-select"
            defaultSelect="Select Width"
            menuPlacement="top"
            onChange={(selectedOption) => setData("width", selectedOption?.value)}
            name="width"
            options={widths}
            value={data.width ? { label: data.width, value: data.width } : null}
        />
        {(errors.width) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.width}
            </div>
        )}
        {/* INPUT TYPE */}
        <DropdownSelect
            placeholder="Select Input Type"
            selectType="react-select"
            defaultSelect="Select Width"
            menuPlacement="top"
            onChange={(selectedOption) => setData((prevData) => ({
                        ...prevData,
                        type: selectedOption?.value,
                        table: ""
                    }))}
            name="input_type"
            options={InputTypes}
            value={data.type ? { label: data.type, value: data.type } : null}
        />
        {(errors.type) && (
            <div className="font-poppins text-xs font-semibold text-red-600">
                {errors.type}
            </div>
        )}

        {data.type == "select" && 
            <>
                <div className='font-semibold text-xs'><span className='text-red-500'>Note: </span>If the Input Type is select, you need to enter the <span className='text-red-500'>Table Name</span>, <span className='text-red-500'>Select Value</span> and <span className='text-red-500'>Select Label</span>.</div>
                {/* TABLE NAME */}
                <DropdownSelect
                    placeholder="Select Table"
                    selectType="react-select"
                    defaultSelect="Select Table"
                    onChange={handleTableChange}
                    name="table_name"
                    menuPlacement="top"
                    options={database_tables_and_columns.map(table => ({ id : table.table_name, name: table.table_name}))}
                    value={data.table ? { label: data.table, value: data.table } : null}
                />
                {(errors.table) && (
                    <div className="font-poppins text-xs font-semibold text-red-600">
                        {errors.table}
                    </div>
                )}
            </>
        }

        {data.table && 
            <>
                <DropdownSelect
                    placeholder="Enter Select Input Value"
                    selectType="react-select"
                    defaultSelect="Enter Select Input Value"
                    onChange={(selectedOption) => setData("table_select_value", selectedOption?.value)}
                    name="select_input_value"
                    menuPlacement="top"
                    options={columns}
                    value={data.table_select_value ? { label: data.table_select_value, value: data.table_select_value } : null}
                />
                {(errors.table_select_value) && (
                    <div className="font-poppins text-xs font-semibold text-red-600">
                        {errors.table_select_value}
                    </div>
                )}
                <DropdownSelect
                    placeholder="Enter Select Input Label"
                    selectType="react-select"
                    defaultSelect="Enter Select Input Label"
                    onChange={(selectedOption) => setData("table_select_label", selectedOption?.value)}
                    name="select_input_label"
                    menuPlacement="top"
                    options={columns}
                    value={data.table_select_label ? { label: data.table_select_label, value: data.table_select_label } : null}
                />
                {(errors.table_select_label) && (
                    <div className="font-poppins text-xs font-semibold text-red-600">
                        {errors.table_select_label}
                    </div>
                )}

                {/* HEADER NAME */}
                <InputComponent
                    name="table_join"
                    value={data.table_join}
                    disabled={action === 'View'}
                    placeholder="Enter Table Join (ex. get_table.table_column)"
                    onChange={(e)=> setData("table_join", e.target.value)}
                />
                {(errors.table_join) && (
                    <div className="font-poppins text-xs font-semibold text-red-600">
                        {errors.table_join}
                    </div>
                )}
            </>
        
        }

        
        

        
       
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