import { Head, useForm } from "@inertiajs/react";
import React, { useEffect } from "react";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import ContentPanel from "../../Components/Table/ContentPanel";
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from "../../Components/Table/Buttons/Button";
import InputComponent from "../../Components/Forms/Input";

const ItemMasterApprovalView = ({
    page_title,
    item_master_approval,
    table_headers,
    action,
}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: item_master_approval.id,
        action: "",
    });

    // FORM SUBMIT
    const handleFormSubmit = (e) => {
        e.preventDefault();

        Swal.fire({
            title: `<p class="font-poppins text-3xl" >Do you want to ${data.action} this Item Master?</p>`,
            showCancelButton: true,
            confirmButtonText: `Add Item`,
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                post("/item_masters_approval/approval", {
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
                <div className="border p-4 rounded-lg">
                    <form onSubmit={handleFormSubmit}>
                        <div className="md:grid md:grid-cols-2 md:gap-2 space-y-2 md:space-y-0">
                            {table_headers?.map((header, index) => (
                                <InputComponent
                                    key={index}
                                    type="text"
                                    name={header.name}
                                    displayName={header.header_name}
                                    value={
                                        item_master_approval?.item_values?.[
                                            header.name
                                        ] ?? ""
                                    }
                                    disabled={true}
                                />
                            ))}
                        </div>
                        <div className="flex justify-between mt-4">
                            <Button
                                type="link"
                                href="/item_masters_approval"
                                extendClass={`${
                                    theme === "bg-skin-white"
                                        ? primayActiveColor
                                        : theme
                                }`}
                                fontColor={textColorActive}
                            >
                                <span>Back</span>
                            </Button>
                            {action === "approval" && (
                                <div className="inline-flex gap-1">
                                    <Button
                                        type="button"
                                        onClick={() =>
                                            setData("action", "reject")
                                        }
                                        extendClass={`${
                                            theme === "bg-skin-white"
                                                ? primayActiveColor
                                                : theme
                                        }`}
                                        fontColor={textColorActive}
                                        disabled={processing}
                                    >
                                        {processing ? (
                                            "Processing..."
                                        ) : (
                                            <span>
                                                <i
                                                    className={`fa-solid fa-times mr-1`}
                                                ></i>
                                                REJECT
                                            </span>
                                        )}
                                    </Button>

                                    <Button
                                        type="button"
                                        onClick={() =>
                                            setData("action", "approve")
                                        }
                                        extendClass={`${
                                            theme === "bg-skin-white"
                                                ? primayActiveColor
                                                : theme
                                        }`}
                                        fontColor={textColorActive}
                                        disabled={processing}
                                    >
                                        {processing ? (
                                            "Processing..."
                                        ) : (
                                            <span>
                                                <i
                                                    className={`fa-solid fa-check mr-1`}
                                                ></i>
                                                APPROVE
                                            </span>
                                        )}
                                    </Button>
                                </div>
                            )}
                        </div>
                    </form>
                </div>
            </ContentPanel>
        </>
    );
};

export default ItemMasterApprovalView;
