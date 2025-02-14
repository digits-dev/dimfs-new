import { Head, Link, router, useForm } from "@inertiajs/react";
import React, { useState, useEffect } from "react";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from "../../Components/Table/Buttons/Button";
import ContentPanel from "../../Components/Table/ContentPanel";
import axios from "axios";
import DropdownSelect from "../../Components/Dropdown/Dropdown";
import CheckboxWithText from "../../Components/Checkbox/CheckboxWithText";
import LoadingIcon from "../../Components/Table/Icons/LoadingIcon";

const CreateTableSetting = ({ privileges, action_types }) => {
    const { theme } = useTheme();
    const [moduleData, setModuleData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const { handleToast } = useToast();

    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

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
            id: "38",
            name: "Item Master",
        },
        {
            id: "28",
            name: "Gashapon Item Master",
        },
    ];

    const handleCheckboxChange = (item) => {
        setData((prevData) => ({
            ...prevData,
            checked_items: prevData.checked_items.includes(item)
                ? prevData.checked_items.filter((i) => i !== item)
                : [...prevData.checked_items, item],
        }));
    };

    const handleSelectAllChange = () => {
        if (moduleData) {
            const allSelected = data.checked_items.length === moduleData.length;
            setData((prevData) => ({
                ...prevData,
                checked_items: allSelected ? [] : moduleData,
            }));
        }
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

    const handleFormSubmit = (e) => {
        e.preventDefault();
        Swal.fire({
            title: `<p class="font-poppins text-3xl" >Do you want to create Table Setting?</p>`,
            showCancelButton: true,
            confirmButtonText: `Create Table`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                post("/table_settings/create", {
                    onSuccess: (data) => {
                        const { message, type } = data.props.auth.sessions;
                        handleToast(message, type);
                    },
                    onError: (data) => {
                        const { message, type } = data.props.auth.sessions;
                        handleToast(message, type);
                    },
                });
            }
        });
    };

    return (
        <div className="h-full font-poppins">
            <Head title="Create Table Setting" />
            <ContentPanel>
                <p className="text-lg font-semibold mb-2">
                    Create Table Setting
                </p>
                <form className="space-y-4" onSubmit={handleFormSubmit}>
                    <div className="flex flex-col md:flex-row justify-center items-stretch gap-4 h-full">
                        {/* CARD 1 */}
                        <div className="md:w-[30%] flex flex-col min-h-full border rounded-lg p-4 border-gray-300">
                            <DropdownSelect
                                placeholder="Select Module"
                                selectType="react-select"
                                defaultSelect="Select Module"
                                onChange={(selectedOption) =>
                                    setData((prevData) => ({
                                        ...prevData,
                                        module_id: selectedOption?.value,
                                        module_name: selectedOption?.label,
                                        checked_items: [],
                                    }))
                                }
                                name="module_name"
                                options={modules}
                                value={
                                    data.module_id
                                        ? {
                                              label: data.module_name,
                                              value: data.module_id,
                                          }
                                        : null
                                }
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
                                onChange={(selectedOption) =>
                                    setData((prevData) => ({
                                        ...prevData,
                                        action_type_id: selectedOption?.value,
                                        action_name: selectedOption?.label,
                                    }))
                                }
                                name="action_type"
                                options={action_types}
                                value={
                                    data.action_type_id
                                        ? {
                                              label: data.action_type_id,
                                              value: data.action_type_id,
                                          }
                                        : null
                                }
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
                                onChange={(selectedOption) =>
                                    setData((prevData) => ({
                                        ...prevData,
                                        privilege_id: selectedOption?.value,
                                        privilege_name: selectedOption?.label,
                                    }))
                                }
                                name="privilege Name"
                                options={privileges}
                                value={
                                    data.privilege_id
                                        ? {
                                              label: data.privilege_id,
                                              value: data.privilege_id,
                                          }
                                        : null
                                }
                            />
                            {errors.privilege_id && (
                                <div className="font-poppins text-xs font-semibold text-red-600">
                                    {errors.privilege_id}
                                </div>
                            )}
                        </div>

                        {/* CARD 2 */}
                        <div className="md:w-[70%] flex-1 flex flex-col min-h-full border rounded-lg p-4 border-gray-300">
                            {loading ? (
                                <div className="w-full h-full flex items-center justify-center">
                                    <LoadingIcon />
                                </div>
                            ) : (
                                <>
                                    {moduleData ? (
                                        <>
                                            {/* Select All Checkbox */}
                                            <div className="mb-2">
                                                <CheckboxWithText
                                                    id="select-all"
                                                    type="checkbox"
                                                    name="selectAll"
                                                    text="Select All"
                                                    textColor="text-black font-bold"
                                                    handleClick={
                                                        handleSelectAllChange
                                                    }
                                                    isChecked={
                                                        data.checked_items
                                                            .length ===
                                                        moduleData.length
                                                    }
                                                    disabled={false}
                                                />
                                            </div>

                                            {/* Individual Checkboxes */}
                                            <div className="grid grid-cols-3">
                                                {moduleData.map(
                                                    (item, index) => (
                                                        <div key={item}>
                                                            <CheckboxWithText
                                                                id={`checkbox-${index}`}
                                                                type="checkbox"
                                                                name="exampleCheckbox"
                                                                text={item}
                                                                textColor="text-black"
                                                                handleClick={() =>
                                                                    handleCheckboxChange(
                                                                        item
                                                                    )
                                                                }
                                                                isChecked={data.checked_items.includes(
                                                                    item
                                                                )}
                                                                disabled={false}
                                                            />
                                                        </div>
                                                    )
                                                )}
                                            </div>
                                        </>
                                    ) : (
                                        <div className="select-none w-full h-full flex items-center justify-center border border-dashed rounded-lg border-gray-300">
                                            <p className="font-semibold text-gray-300">
                                                Please Select Module
                                            </p>
                                        </div>
                                    )}
                                </>
                            )}
                            {error && <p className="text-red-500">{error}</p>}
                        </div>
                    </div>
                    <div className="flex justify-between">
                        <Button
                            type="link"
                            href="/table_settings"
                            extendClass={`${
                                theme === "bg-skin-white"
                                    ? primayActiveColor
                                    : theme
                            }`}
                            fontColor={textColorActive}
                            disabled={processing}
                        >
                                <span>
                                    Back
                                </span>
                        </Button>
                        <Button
                            type="button"
                            extendClass={`${
                                theme === "bg-skin-white"
                                    ? primayActiveColor
                                    : theme
                            }`}
                            fontColor={textColorActive}
                            disabled={processing}
                        >
                            {processing ? (
                                "Create Table"
                            ) : (
                                <span>
                                    <i className={`fa-solid fa-plus mr-1`}></i>{" "}
                                    Create Table
                                </span>
                            )}
                        </Button>
                    </div>
                </form>
            </ContentPanel>
        </div>
    );
};

export default CreateTableSetting;
