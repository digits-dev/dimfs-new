import { Head, useForm } from "@inertiajs/react";
import React, { useEffect } from "react";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import ContentPanel from "../../Components/Table/ContentPanel";
import MultiTypeInput from "../../Components/Forms/MultiTypeInput";
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from "../../Components/Table/Buttons/Button";

const RmaItemMasterUpdate = ({
    page_title,
    update_inputs,
    rma_item_master_detail,
    table_setting_read_only,
}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const initialFormData = update_inputs.reduce(
        (acc, item) => {
            acc[item.name] = "" || rma_item_master_detail[item.name];
            return acc;
        },
        { validation: "", id: rma_item_master_detail.id }
    );

    const { data, setData, processing, reset, post, errors } =
        useForm(initialFormData);

    // APPLYING VALIDATION
    useEffect(() => {
        console.log(rma_item_master_detail, update_inputs);
        setData(
            "validation",
            Object.fromEntries(
                update_inputs.map((input) => [input.name, input.validation])
            )
        );
    }, []);

    // ONCHANGE
    const handleInputChange = (name, type, selectedValue) => {
        if (type == "text" || type == "date") {
            setData(name, selectedValue.target.value);
        } else {
            setData((prevData) => ({
                ...prevData,
                [name]: selectedValue?.value,
            }));
        }
    };

    // FORM SUBMIT
    const handleFormSubmit = (e) => {
        e.preventDefault();
        Swal.fire({
            title: `<p class="font-poppins text-3xl" >Do you want to update Item?</p>`,
            showCancelButton: true,
            confirmButtonText: `Update Item`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                post("/rma_item_masters/update", {
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
        <>
            <Head title={page_title} />
            <ContentPanel>
                <p className="text-lg font-semibold mb-2">
                    Update Item Master Data
                </p>
                <div className="border p-4 rounded-lg">
                    {update_inputs && (
                        <form onSubmit={handleFormSubmit}>
                            <div className="md:grid md:grid-cols-2 md:gap-2 space-y-2 md:space-y-0">
                                {update_inputs.map((input, index) => (
                                    <div key={index} className="w-full">
                                        <MultiTypeInput
                                            name={input.name}
                                            type={input.type}
                                            disabled={table_setting_read_only.includes(
                                                input.header_name
                                            )}
                                            value={
                                                input.type == "select"
                                                    ? {
                                                          label: input.table_join
                                                              .split(".")
                                                              .reduce(
                                                                  (acc, key) =>
                                                                      acc?.[
                                                                          key
                                                                      ],
                                                                  rma_item_master_detail
                                                              ),
                                                          value: rma_item_master_detail[
                                                              input.name
                                                          ],
                                                      }
                                                    : data[input.name]
                                            }
                                            onChange={(selectedValue) =>
                                                handleInputChange(
                                                    input.name,
                                                    input.type,
                                                    selectedValue
                                                )
                                            }
                                            displayName={input.header_name}
                                            placeholder={`Enter ${input.header_name}`}
                                            selectInputOptions={
                                                input.table_data
                                                    ? input.table_data
                                                    : []
                                            }
                                        />
                                        {errors[input.name] && (
                                            <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                                                {errors[input.name]}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>

                            <div className="flex justify-between mt-4">
                                <Button
                                    type="link"
                                    href="/rma_item_masters"
                                    extendClass={`${
                                        theme === "bg-skin-white"
                                            ? primayActiveColor
                                            : theme
                                    }`}
                                    fontColor={textColorActive}
                                    disabled={processing}
                                >
                                    <span>Back</span>
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
                                            <i
                                                className={`fa-solid fa-edit mr-1`}
                                            ></i>{" "}
                                            Update Item
                                        </span>
                                    )}
                                </Button>
                            </div>
                        </form>
                    )}
                </div>
            </ContentPanel>
        </>
    );
};

export default RmaItemMasterUpdate;
