import { Head, router, useForm } from "@inertiajs/react";
import React, { useState, useEffect } from "react";
import { useTheme } from "../../Context/ThemeContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from "../../Components/Table/Buttons/Button";
import ContentPanel from "../../Components/Table/ContentPanel";
import axios from "axios";
import DropdownSelect from "../../Components/Dropdown/Dropdown";
import CheckboxWithText from "../../Components/Checkbox/CheckboxWithText";
import LoadingIcon from "../../Components/Table/Icons/LoadingIcon";
import { useToast } from "../../Context/ToastContext";

const UpdateTableSetting = ({
    table_settings,
    module_headers,
    privileges,
    action_types,
}) => {
    const { theme } = useTheme();
    const [moduleData, setModuleData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const { handleToast } = useToast();

    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const actionName =
        action_types.find(
            (action) => action.id === table_settings.action_types_id
        )?.name || "";

    const privilegeName =
        privileges.find(
            (privilege) => privilege.id === table_settings.adm_privileges_id
        )?.name || "";

    const reportHeaders = table_settings.report_header.split(",");

    const { data, setData, processing, reset, post, errors } = useForm({
        id: table_settings.id,
        module_id: table_settings.adm_moduls_id,
        module_name: table_settings.table_name,
        action_type_id: table_settings.action_types_id,
        action_name: actionName,
        privilege_id: table_settings.adm_privileges_id,
        privilege_name: privilegeName,
        checked_items: reportHeaders,
        status: table_settings.status,
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
        {
            id: "79",
            name: "RMA Item Master",
        },
    ];

    const statuses = [
        {
            id: "ACTIVE",
            name: "ACTIVE",
        },
        {
            id: "INACTIVE",
            name: "INACTIVE",
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
            title: `<p class="font-poppins text-3xl" >Do you want to update Table Setting?</p>`,
            showCancelButton: true,
            confirmButtonText: `Update Table`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                post("/table_settings/update", {
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
            <Head title="Update Table Setting" />
            <ContentPanel>
                <p className="text-lg font-semibold mb-2">
                    Update Table Setting
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
                                value={{
                                    label: table_settings.table_name,
                                    value: table_settings.adm_moduls_id,
                                }}
                            />

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
                                value={{
                                    label: data.action_name,
                                    value: data.action_type_id,
                                }}
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
                                value={{
                                    label: data.privilege_name,
                                    value: table_settings.adm_privileges_id,
                                }}
                            />
                            {errors.privilege_id && (
                                <div className="font-poppins text-xs font-semibold text-red-600">
                                    {errors.privilege_id}
                                </div>
                            )}
                            <DropdownSelect
                                addMainClass="mt-3"
                                placeholder="Choose Status"
                                selectType="react-select"
                                defaultSelect="Select Status"
                                onChange={(selectedOption) =>
                                    setData("status", selectedOption?.value)
                                }
                                name="status"
                                options={statuses}
                                value={
                                    data.status
                                        ? {
                                              label: data.status,
                                              value: data.status,
                                          }
                                        : null
                                }
                            />
                            {errors.status && (
                                <div className="font-poppins text-xs font-semibold text-red-600">
                                    {errors.status}
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
                                    {/* Select All Checkbox */}
                                    <div className="mb-2">
                                        <CheckboxWithText
                                            id="select-all"
                                            type="checkbox"
                                            name="selectAll"
                                            text="Select All"
                                            textColor="text-black font-bold"
                                            handleClick={handleSelectAllChange}
                                            isChecked={
                                                moduleData
                                                    ? data.checked_items
                                                          .length ===
                                                      moduleData.length
                                                    : data.checked_items
                                                          .length ===
                                                      module_headers.length
                                            }
                                            disabled={false}
                                        />
                                    </div>

                                    {moduleData ? (
                                        <>
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
                                        <div className="grid grid-cols-3">
                                            {module_headers.map(
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
                                "Update Table"
                            ) : (
                                <span>
                                    <i className={`fa-solid fa-plus mr-1`}></i>{" "}
                                    Update Table
                                </span>
                            )}
                        </Button>
                    </div>
                </form>
            </ContentPanel>
        </div>
    );
};

export default UpdateTableSetting;
