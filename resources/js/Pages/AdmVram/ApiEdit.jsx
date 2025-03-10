import React, { useEffect, useState } from 'react';
import { Head, Link, router, usePage } from "@inertiajs/react";
import InputComponent from '../../Components/Forms/Input';
import CustomSelect from '../../Components/Dropdown/CustomSelect';
import { values } from 'lodash';
import RowAction from '../../Components/Table/RowAction';
import LoginInputTooltip from '../../Components/Tooltip/LoginInputTooltip';
import TextArea from '../../Components/Forms/TextArea';
import Button from '../../Components/Table/Buttons/Button';
import { useToast } from '../../Context/ToastContext';
import Modalv2 from '../../Components/Modal/Modalv2';
import ContentPanel from '../../Components/Table/ContentPanel';

const ApiEdit = ({page_title, api, database_tables_and_columns}) => {
    const baseUrl = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : "" + "/api/");
    const [endpoint, setEndpoint] = useState("");
    const { handleToast } = useToast();
    const [showModal, setShowModal] = useState(false);

    const handleModalToggle = ()=> {
        setShowModal(!showModal);
    };

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

    useEffect(() => {
        if (api && api.length > 0) {
            const defaultAction = { label: api[0].action_type, value: api[0].action_type };
            handleSelectChange(defaultAction);
        }
    }, [api]);

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
    const [allColumns, setAllColumns] = useState([]);  

    const handleTableChange = (selectedOption) => {
        setSelectedTable(selectedOption.value);
    
        // Get all columns for the selected table
        const columns = database_tables_and_columns.find(table => table.table_name === selectedOption.value)?.columns || [];
    
        setAllColumns(columns);

        // Filter default columns
        const apiFields = api?.[0]?.fields ? Object.keys(api[0].fields) : [];
        const filteredColumns = columns.filter(col => apiFields.includes(col));

        if(selectedOption.value === api?.[0].table_name) {
            setSelectedColumns(filteredColumns);
        } else {
            setSelectedColumns(columns);
        }
    };

    // Auto-select the table if available
    useEffect(() => {
        if (api && api.length > 0) {
            const defaultTable = { label: api[0].table_name, value: api[0].table_name };
            handleTableChange(defaultTable);
        }
    }, [api]); 

    const [columnValidations, setColumnValidations] = useState({});
    const [columnRelations, setColumnRelations] = useState(() => {
        const initialRelations = {};
    
        selectedColumns.forEach((column, index) => {
            const relation = api[0]?.relations[column]; // Get relation from API
            if (relation) {
                initialRelations[index] = {
                    table: relation.table || "",
                    column: relation.column || "",
                    column_get: relation.column_get || ""
                };
            }
        });
    
        return initialRelations;
    });
    

    const handleRelationTableChange = (selectedOption, index) => {
        const relationColumns = database_tables_and_columns.find(table => table.table_name === selectedOption.value)?.columns || [];
    
        setColumnRelations(prev => ({
            ...prev,
            [index]: {
                table: selectedOption.value,
                column: relationColumns.length > 0 ? relationColumns[0] : null,
                column_get: relationColumns.length > 0 ? relationColumns[0] : null
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

    const handleDisplayColumnChange = (selectedOption, index) => {
        setColumnRelations(prev => ({
            ...prev,
            [index]: {
                ...prev[index],
                column_get: selectedOption.value
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
    
    const handleSubmit = () => { 
    
        const fields = {}; 
        const fields_relations = {}; 
        const fields_validations = {};
    
        selectedColumns.forEach((column, index) => {
            fields[column] = column; // JSON 1: All column names
            if (columnRelations[index]) {
                fields_relations[column] = columnRelations[index]; // JSON 2: Column relations
            }
            if (columnValidations[index]) {
                fields_validations[column] = columnValidations[index]; // JSON 3: Column validations
            }
        });
    
        const formData = {
            api_name: document.querySelector("[name='api_name']").value,
            api_endpoint: endpoint,
            table: selectedTable,
            action_type: actionType,
            api_method: selectedOption,
            sql_where: document.querySelector("[name='sql_where']").value,
            fields, 
            fields_relations, 
            fields_validations 
        };
    
        // router.post('api_generator/create_api', formData, {
        //     onSuccess: (data) => {
        //         const { message, type } = data.props.auth.sessions;
        //         handleToast(message, type);
        //         router.reload();
        //         reset();
        //         onClose();
        //     },
        //     onError: (error) => {},
        // });

    };  

    Object.entries(api[0].relations).forEach(([column, relation]) => {
        console.log(`Column: ${column}`);
        console.log(`Table: ${relation.table}, Column: ${relation.column}, Column Get: ${relation.column_get}`);
    });

    return (
        <>
        <Head title={page_title}/>
            <ContentPanel>
            <div className='md:grid md:grid-cols-7 md:gap-4'>
                <div className='md:col-span-3'>
                    <InputComponent 
                        type='text'
                        displayName='API Name'
                        name='api_name'
                        placeholder='Enter API Name'
                        value={api?.[0].name}
                    />
                </div>
                <div className='md:col-span-2'>
                    <InputComponent 
                        type='text'
                        displayName='API Endpoint'
                        name='api_endpoint'
                        id="api_endpoint"
                        value={api?.[0].endpoint || endpoint}
                        onChange={(e) => setEndpoint(e.target.value)}
                        placeholder='Enter API Endpoint'
                    />
                </div>
                <div className='md:col-span-2'>
                    <CustomSelect 
                        onChange={handleTableChange}
                        options={database_tables_and_columns.map(table => ({ label : table.table_name, value: table.table_name}))}
                        value={api ? { label: api?.[0].table_name, value: api?.[0].table_name } : null}
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
                        value={api ? { label: api?.[0].action_type, value: api?.[0].action_type } : null}
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
                <hr className='border' />
                
                <p className='mt-1'>
                    <i className='fa fa-cogs me-1'></i>
                    Parameters
                </p>
            </div>

            {selectedColumns.map((column, index) => ( 
                <div key={index} className='md:grid md:grid-cols-12 md:gap-4 border p-3 pb-4 rounded mb-2'>

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
                    <div className='md:col-span-6'>
                        <div className='md:grid md:grid-cols-3 md:gap-1'>
                            <div className='md:col-span-1'>
                                <CustomSelect 
                                    onChange={(selected) => handleRelationTableChange(selected, index)}
                                    options={database_tables_and_columns.map(table => ({ label: table.table_name, value: table.table_name }))}
                                    selectType="react-select"
                                    placeholder='Table'
                                    displayName='Table Relation'
                                    name='table_relation'
                                />
                            </div>
                            <div className='md:col-span-1'>
                                <CustomSelect 
                                    onChange={(selected) => handleRelationColumnChange(selected, index)}
                                    options={(columnRelations[index]?.table
                                        ? database_tables_and_columns.find(table => table.table_name === columnRelations[index]?.table)?.columns
                                        : []).map(col => ({ label: col, value: col }))}
                                    selectType="react-select"
                                    placeholder='Column'
                                    displayName='Column Relation'
                                    name='column_relation'
                                    value={columnRelations[index]?.column || ""}
                                />
                            </div>
                            <div className='md:col-span-1'>
                                <CustomSelect 
                                    onChange={(selected) => handleDisplayColumnChange(selected, index)}
                                    options={(columnRelations[index]?.table
                                        ? database_tables_and_columns.find(table => table.table_name === columnRelations[index]?.table)?.columns
                                        : []).map(col => ({ label: col, value: col }))}
                                    selectType="react-select"
                                    placeholder='Display Col'
                                    displayName='Display Relation'
                                    name='display_relation'
                                    value={columnRelations[index]?.column_get || ""}
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
                <hr className='border mb-2' />

                <TextArea 
                    rows='5'
                    name='sql_where'
                    displayName='SQL Where Query (Optional)'
                    placeholder='Enter Query here... e.g.(id = [paramId])'
                    value={api?.[0].sql_parameter}
                />
                <small>
                    <i className='fa fa-info-circle me-1 text-sky-500'></i>
                    <b className='text-sky-500'>NOTE: </b> 
                        If you have table relations in you Parameters please specify what 
                        table you were dealing with your SQL Where Query here. 
                    <b> E.g. (table.column = [value])</b>
                </small>
            </div>  
            <div className='float-end mt-3'>
                <Button
                    extendClass="bg-skin-blue"
                    fontColor="text-white"
                    type="button"
                    onClick={handleModalToggle}
                >
                    <i className="fa fa-code-merge text-base px-[1px] me-1"></i>
                    Update Generated API
                </Button>
            </div>

            <Modalv2
                title="API Creation Confirmation"
                content="Are you sure you want to Save/Generate this new API?"
                confirmButtonName="Generate"
                confirmButtonColor="bg-green-500"
                isOpen={showModal}
                onConfirm={handleSubmit}
                setIsOpen={handleModalToggle}
            />
        </ContentPanel>
    </>
    );
};

export default ApiEdit;

