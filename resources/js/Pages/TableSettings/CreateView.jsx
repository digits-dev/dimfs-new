import { Head, router } from "@inertiajs/react";
import React, { useState, useEffect } from "react";
import { useTheme } from "../../Context/ThemeContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import ContentPanel from "../../Components/Table/ContentPanel";
import axios from "axios";

const CreateTableSetting = ({ privileges, action_types }) => {
    const [selectedModule, setSelectedModule] = useState("");
    const [moduleData, setModuleData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [checkedItems, setCheckedItems] = useState([]);

    const handleCheckboxChange = (item) => {
        setCheckedItems((prev) =>
            prev.includes(item)
                ? prev.filter((i) => i !== item)
                : [...prev, item]
        );
    };

    console.log(checkedItems);
    useEffect(() => {
        if (selectedModule) {
            setLoading(true);
            setError(null);

            axios
                .get(`/table_settings/get-header/${selectedModule}`)
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
    }, [selectedModule]);
    return (
        <>
            <Head title="Create Table Setting" />
            <ContentPanel>
                <h2 className="text-2xl font-semibold mb-4">
                    Create Table Setting
                </h2>
                <form className="space-y-4">
                    <div className="flex justify-center items-center gap-4">
                        <div className="w-[30%] h-80">
                            <div class="border border-gray-300 rounded-lg p-4 shadow-sm w-full h-full">
                                <label class="block text-gray-700 text-sm font-medium mb-1">
                                    Module
                                </label>
                                <select
                                    className="w-full border border-gray-300 rounded-md p-1 focus:ring focus:ring-blue-300"
                                    value={selectedModule}
                                    onChange={(e) =>
                                        setSelectedModule(e.target.value)
                                    }
                                >
                                    <option value="">Select Module</option>
                                    <option value="38">Item Master</option>
                                    <option value="28">
                                        Gashapon Item Master
                                    </option>
                                </select>
                                <label class="block text-gray-700 text-sm font-medium mt-4 mb-1">
                                    Action Type
                                </label>
                                <select class="w-full border border-gray-300 rounded-md p-1 focus:ring focus:ring-blue-300">
                                    <option>Select Action Type</option>
                                    {action_types.map((action_type) => (
                                        <option key={action_type.id}>
                                            {
                                                action_type.action_type_description
                                            }
                                        </option>
                                    ))}
                                </select>
                                <label class="block text-gray-700 text-sm font-medium mt-4 mb-1">
                                    Privilege
                                </label>
                                <select class="w-full border border-gray-300 rounded-md p-1 focus:ring focus:ring-blue-300">
                                    <option>Select Privilege</option>
                                    {privileges.map((privilege) => (
                                        <option key={privilege.id}>
                                            {privilege.name}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                        <div className="w-[80%] h-80">
                            <div className="border border-gray-300 rounded-lg p-4 shadow-sm w-full h-full">
                                {loading && <p>Loading data...</p>}
                                {error && (
                                    <p className="text-red-500">{error}</p>
                                )}
                                <ul>
                                    {moduleData?.map((item, index) => (
                                        <li
                                            key={index}
                                            className="border-b p-2 flex items-center gap-2"
                                        >
                                            <input
                                                type="checkbox"
                                                className="form-checkbox h-4 w-4 text-blue-600"
                                                checked={checkedItems.includes(
                                                    item
                                                )}
                                                onChange={() =>
                                                    handleCheckboxChange(item)
                                                }
                                            />
                                            {item}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </ContentPanel>
        </>
    );
};

export default CreateTableSetting;
