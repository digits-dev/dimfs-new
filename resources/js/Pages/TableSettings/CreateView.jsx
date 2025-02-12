import { Head, router, useForm } from "@inertiajs/react";
import React, { useState, useEffect } from "react";
import { useTheme } from "../../Context/ThemeContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import ContentPanel from "../../Components/Table/ContentPanel";
import axios from "axios";
import DropdownSelect from "../../Components/Dropdown/Dropdown";
import CheckboxWithText from "../../Components/Checkbox/CheckboxWithText";
import LoadingIcon from "../../Components/Table/Icons/LoadingIcon";

const CreateTableSetting = ({ privileges, action_types }) => {
    const [moduleData, setModuleData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

     const { data, setData, processing, reset, post, errors } = useForm({
        module_id: "",
        module_name: "",
        action_type_id: "",
        action_name: "",
        privilege_id: "",
        privilege_name: "",
        checked_items: [],
    });

    const modules = [
        {
            id: '38',
            name:'Item Master',
        },
        {
            id: '28',
            name:'Gashapon Item Master',
        },
    ]

    useEffect(()=>{
        console.log(data);
    },[data]);


    const handleCheckboxChange = (item) => {
        setData((prevData) => ({
            ...prevData,
            checked_items: prevData.checked_items.includes(item)
                ? prevData.checked_items.filter((i) => i !== item)
                : [...prevData.checked_items, item],
        }));
    };

    useEffect(() => {
        if (data.module_id) {
            
            setLoading(true);
            setError(null);
            axios
                .get(`/table_settings/get_header/${data.module_id}`)
                .then((response) => {
                    setModuleData(response.data);
                })
                .catch((error) => {
                    console.error("Error fetching module data:", error);
                    setError("Failed to load data");
                })
                .finally(() => {
                    setLoading(false);
                });
        }
    }, [data.module_id]);


    return (
        <div className="h-full font-poppins">
            <Head title="Create Table Setting" />
            <ContentPanel>
                <p className="text-lg font-semibold mb-2">
                    Create Table Setting
                </p>
                <form className="space-y-4">
                    <div className="flex justify-center items-stretch gap-4 h-full">
                        {/* CARD 1 */}
                        <div className="w-[30%] flex flex-col min-h-full border rounded-lg p-4 border-gray-300">
                            <DropdownSelect
                                placeholder="Select Module"
                                selectType="react-select"
                                defaultSelect="Select Module"
                                onChange={(selectedOption) => setData((prevData) => ({
                                    ...prevData,
                                    module_id: selectedOption?.value,
                                    module_name: selectedOption?.label,
                                    checked_items: [],
                                }))}
                                name="module_name"
                                options={modules}
                                value={data.module_id ? { label: data.module_name, value: data.module_id } : null}
                            />
                            {errors.module_name && (
                                <div className="font-poppins text-xs font-semibold text-red-600">
                                    {errors.module_name}
                                </div>
                            )}

                            <DropdownSelect
                                addMainClass="mt-3"
                                placeholder="Select Action Type"
                                selectType="react-select"
                                defaultSelect="Select Action Type"
                                onChange={(selectedOption) => setData("action_type_id", selectedOption?.value)}
                                name="action_type"
                                options={action_types}
                                value={data.action_type_id ? { label: data.action_type_id, value: data.action_type_id } : null}
                            />
                            {errors.action_type_id && (
                                <div className="font-poppins text-xs font-semibold text-red-600">
                                    {errors.action_type_id}
                                </div>
                            )}

                            <DropdownSelect
                                addMainClass="mt-3"
                                placeholder="Select Privilege"
                                selectType="react-select"
                                defaultSelect="Select Privilege"
                                onChange={(selectedOption) => setData("privilege_id", selectedOption?.value)}
                                name="privilege Name"
                                options={privileges}
                                value={data.privilege_id ? { label: data.privilege_id, value: data.privilege_id } : null}
                            />
                            {errors.privilege_id && (
                                <div className="font-poppins text-xs font-semibold text-red-600">
                                    {errors.privilege_id}
                                </div>
                            )}
                        </div>

                        {/* CARD 2 */}
                        <div className="w-[70%] flex-1 flex flex-col min-h-full border rounded-lg p-4 border-gray-300">
                            {loading ? 
                                <div className="w-full h-full flex items-center justify-center">
                                    <LoadingIcon/>
                                </div>

                                :
                                <>
                                    {moduleData ? 
                                        moduleData?.map((item, index) => (
                                            <CheckboxWithText
                                                id={`checkbox-${index}`}         
                                                type="checkbox"             
                                                name="exampleCheckbox"
                                                text={item}
                                                textColor="text-black" 
                                                handleClick={() => handleCheckboxChange(item)} 
                                                isChecked={data.checked_items.includes(item)}        
                                                disabled={false}       
                                            />
                                        )) 
                                    :

                                    <div className="select-none w-full h-full flex items-center justify-center border border-dashed rounded-lg border-gray-300">
                                        <p className="font-semibold text-gray-300">Please Select Module</p>
                                    </div>
                                    
                                    }
                                </>
                            }
                            {error && <p className="text-red-500">{error}</p>}

                        </div>
                    </div>
                </form>
            </ContentPanel>
        </div>
    );
};

export default CreateTableSetting;
