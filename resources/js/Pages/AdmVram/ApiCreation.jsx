import React, { useEffect, useState } from 'react';
import { Head, Link, router, usePage } from "@inertiajs/react";
import InputComponent from '../../Components/Forms/Input';
import CustomSelect from '../../Components/Dropdown/CustomSelect';
import { values } from 'lodash';
import RowAction from '../../Components/Table/RowAction';
import LoginInputTooltip from '../../Components/Tooltip/LoginInputTooltip';
import TextArea from '../../Components/Forms/TextArea';
import Button from '../../Components/Table/Buttons/Button';

const ApiCreation = ({table_x_columns}) => {
    const baseUrl = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : "" + "/api/");
    const [endpoint, setEndpoint] = useState("");

    const [selectedOption, setSelectedOption] = useState("");
    const [actionType, setActionType] = useState("");

    const handleSelectChange = (selected) => {
        setActionType(selected.value);

        if (selected.value === "CREATE/POST" || selected.value === "UPDATE") {
          setSelectedOption("post");
        } else if (selected.value === "LISTING/GET"){
            setSelectedOption("get");
        } else if (selected.value === "DELETE"){
            setSelectedOption("delete");
        } else {
            setSelectedOption("");
        }
      };

    const handleChange = (event) => {
        setSelectedOption(event.target.value);
    };

    const items = [
        {
            label:'LISTING/GET',
            value:'LISTING/GET'
        },
        {
            label:'CREATE/POST',
            value:'CREATE/POST'
        },
        {
            label:'UPDATE',
            value:'UPDATE'
        },
        {
            label:'DELETE',
            value:'DELETE'
        },
    ];

    const [selectedTable, setSelectedTable] = useState(null);
    const [selectedColumns, setSelectedColumns] = useState([]);
    const [allColumns, setAllColumns] = useState([]);  // Store all available columns

    const handleTableChange = (selectedOption) => {
        setSelectedTable(selectedOption.value);

        // Get all columns for the selected table
        const columns = table_x_columns.find(table => table.table_name === selectedOption.value)?.columns || [];
        
        setAllColumns(columns); // Store full column list
        setSelectedColumns(columns); // Initially select all columns
    };

    const [columnRelations, setColumnRelations] = useState({});
    const [columnValidations, setColumnValidations] = useState({});


    const handleRelationTableChange = (selectedOption, index) => {
        const relationColumns = table_x_columns.find(table => table.table_name === selectedOption.value)?.columns || [];
    
        setColumnRelations(prev => ({
            ...prev,
            [index]: {
                table: selectedOption.value,
                column: relationColumns.length > 0 ? relationColumns[0] : null // Default to first column
            }
        }));
    };
    
    const handleRelationColumnChange = (selectedOption, index) => {
        setColumnRelations(prev => ({
            ...prev,
            [index]: {
                ...prev[index],
                column: selectedOption.value
            }
        }));
    };
    
    const handleValidationChange = (event, index) => {
        setColumnValidations(prev => ({
            ...prev,
            [index]: event.target.value
        }));
    };
    

    const handleRemoveColumn = (indexToRemove) => {
        setSelectedColumns(prevColumns => {
            if (prevColumns.length > 1) { // Ensure at least one remains
                return prevColumns.filter((_, index) => index !== indexToRemove);
            } else {
                return prevColumns; 
            }
        });
    };
    
    const handleAddColumn = () => {
        const missingColumn = allColumns.find(col => !selectedColumns.includes(col));
        if (missingColumn) {
            setSelectedColumns(prevColumns => [...prevColumns, missingColumn]);
        }
    };   
    
    const handleSubmit = (e) => {
        e.preventDefault(); 
    
        const formData = {
            api_name: document.querySelector("[name='api_name']").value,
            api_endpoint: endpoint,
            table: selectedTable,
            action_type: actionType,
            api_method: selectedOption,
            sql_where: document.querySelector("[name='sql_where']").value,
            selected_columns: selectedColumns.map((column, index) => ({
                name: column, 
                validation: columnValidations[index] || "", 
                relation: columnRelations[index] || null
            }))
        };
    
        router.post('api_generator/create-api', formData, {
            onSuccess: () => alert("API successfully created!"),
            onError: (errors) => console.log("Errors:", errors),
        });
    };    

    return (
        <>
          <form onSubmit={handleSubmit}>
            <div className='md:grid md:grid-cols-7 md:gap-4'>
                <div className='md:col-span-3'>
                    <InputComponent 
                        type='text'
                        displayName='API Name'
                        name='api_name'
                        placeholder='Enter API Name'
                    />
                </div>
                <div className='md:col-span-2'>
                    <InputComponent 
                        type='text'
                        displayName='API Endpoint'
                        name='api_endpoint'
                        id="api_endpoint"
                        value={endpoint}
                        onChange={(e) => setEndpoint(e.target.value)}
                        placeholder='Enter API Endpoint'
                    />
                </div>
                <div className='md:col-span-2'>
                    <CustomSelect 
                        onChange={handleTableChange}
                        options={table_x_columns.map(table => ({ label : table.table_name, value: table.table_name}))}
                        displayName='Table'
                        name='table'
                        selectType="react-select"
                        placeholder='Select Table'
                    />
                </div>
                <div className='md:col-span-3'>
                    <InputComponent 
                        type='text'
                        displayName='API Slug/EndPoint'
                        name='api_slug'
                        value={baseUrl + endpoint}
                        disabled
                    />
                </div>
                <div className='md:col-span-2'>
                    <CustomSelect 
                        options={items}
                        onChange={handleSelectChange}
                        displayName='Action Type'
                        name='action_type'
                        selectType="react-select"
                        placeholder='Select Action Type'
                    />
                </div>
                <div className="md:col-span-2">
                    <label htmlFor="api_method" className="block text-sm font-bold text-gray-700 font-poppins">
                        API Method Type
                    </label>

                    <div className="flex gap-4 mt-2">
                        {/* Toggle: GET */}
                        <label className="flex items-center cursor-pointer">
                        <input
                            type="radio"
                            disabled
                            name="api_method"
                            value="get"
                            className="hidden"
                            checked={selectedOption === "get"}
                            onChange={handleChange}
                        />
                        <div className={`relative w-[35px] h-[20px] bg-gray-300 rounded-full transition ${selectedOption === "get" ? "bg-sky-500" : ""}`}>
                            <div
                            className={`absolute left-1.5 top-1 w-3 h-3 bg-white rounded-full shadow-md transform transition ${selectedOption === "get" ? "translate-x-[12px]" : ""}`}
                            ></div>
                        </div>
                        <span className="ml-2 text-gray-700 font-medium">GET</span>
                        </label>

                        {/* Toggle: POST */}
                        <label className="flex items-center cursor-pointer">
                        <input
                            type="radio"
                            disabled
                            name="api_method"
                            value="post"
                            className="hidden"
                            checked={selectedOption === "post"}
                            onChange={handleChange}
                        />
                        <div className={`relative w-[35px] h-[20px] bg-gray-300 rounded-full transition ${selectedOption === "post" ? "bg-green-500" : ""}`}>
                            <div
                            className={`absolute left-1.5 top-1 w-3 h-3 bg-white rounded-full shadow-md transform transition ${selectedOption === "post" ? "translate-x-[12px]" : ""}`}
                            ></div>
                        </div>
                        <span className="ml-2 text-gray-700 font-medium">POST</span>
                        </label>

                        {/* Toggle: DELETE */}
                        <label className="flex items-center cursor-pointer">
                        <input
                            type="radio"
                            disabled
                            name="api_method"
                            value="delete"
                            className="hidden"
                            checked={selectedOption === "delete"}
                            onChange={handleChange}
                        />
                        <div className={`relative w-[35px] h-[20px] bg-gray-300 rounded-full transition ${selectedOption === "delete" ? "bg-red-500" : ""}`}>
                            <div
                            className={`absolute left-1.5 top-1 w-3 h-3 bg-white rounded-full shadow-md transform transition ${selectedOption === "delete" ? "translate-x-[12px]" : ""}`}
                            ></div>
                        </div>
                        <span className="ml-2 text-gray-700 font-medium">DELETE</span>
                        </label>
                    </div>
                </div>
            </div>

            <div className='pt-2'>
                <hr className='border-2' />
                
                <p className='mt-1'>
                    <i className='fa fa-cogs me-1'></i>
                    Parameters
                </p>
            </div>

            {selectedColumns.map((column, index) => (
                <div key={index} className='md:grid md:grid-cols-12 md:gap-4 border p-3 pb-4 rounded mb-2'>
                    
                    {/* Column No. */}
                    <div className='md:col-span-1'>
                        <p className='block text-sm font-bold text-gray-700 font-poppins'>{index + 1}</p>
                    </div>

                    {/* Fields Name */}
                    <div className='md:col-span-2'>
                        <InputComponent 
                            type='text'
                            id={`column_name_${index}`}
                            name='field_name'
                            displayName='Field Name'
                            value={column}
                        />
                    </div>

                    {/* Relations */}
                    <div className='md:col-span-5'>
                        <div className='md:grid md:grid-cols-2 md:gap-1'>
                            <div className='md:col-span-1'>
                                <CustomSelect 
                                    onChange={(selected) => handleRelationTableChange(selected, index)}
                                    options={table_x_columns.map(table => ({ label: table.table_name, value: table.table_name }))}
                                    selectType="react-select"
                                    placeholder='Table'
                                    displayName='Table Relation'
                                    name='table_relation'
                                    value={columnRelations[index]?.table || ""}
                                />
                            </div>
                            <div>
                                <CustomSelect 
                                    onChange={(selected) => handleRelationColumnChange(selected, index)}
                                    options={(columnRelations[index]?.table
                                        ? table_x_columns.find(table => table.table_name === columnRelations[index]?.table)?.columns
                                        : []).map(col => ({ label: col, value: col }))}
                                    selectType="react-select"
                                    placeholder='Column'
                                    displayName='Column Relation'
                                    name='column_relation'
                                    value={columnRelations[index]?.column || ""}
                                />
                            </div>
                        </div>
                    </div>

                    {/* Laravel Validations */}
                    <div className='md:col-span-3'>
                        <LoginInputTooltip content='e.g. [required|nullable|string|max:10]'>
                            <i className='fa fa-circle-info me-1 float-end text-sky-500'></i>
                        </LoginInputTooltip>
                        <InputComponent 
                            type='text'
                            id={`validation_${index}`}
                            name='laravel_validations'
                            displayName='Laravel Validations'
                            value={columnValidations[index] || ""}
                            onChange={(event) => handleValidationChange(event, index)}
                        />
                    </div>

                    {/* Actions */}
                    <div className='md:col-span-1'>
                        <p className='block text-sm font-bold text-gray-700 font-poppins mb-1 ms-2 text-right me-2'>-</p>
                        <div className='md:flex md:justify-end'>
                            {allColumns.some(col => !selectedColumns.includes(col)) && (
                                <RowAction 
                                    type="button" 
                                    action="add" 
                                    onClick={handleAddColumn} 
                                />
                            )}
                            <RowAction type="button" action="remove" onClick={() => handleRemoveColumn(index)} />
                        </div>
                    </div>

                </div>
            ))}

            <div className='pt-2'>
                <hr className='border-2 mb-2' />

                <TextArea 
                    rows='5'
                    name='sql_where'
                    displayName='SQL Where Query (Optional)'
                    placeholder='Enter Query here... e.g.(WHERE id = [paramId])'
                />
            </div>  
            <div className='float-end mt-3'>
                <Button
                    extendClass="bg-skin-blue"
                    fontColor="text-white"
                    type="button"
                >
                    <i className="fa fa-code-merge text-base px-[1px] me-1"></i>
                    Save & Generate API
                </Button>
            </div>
            </form>
        </>
    );
};

export default ApiCreation;
