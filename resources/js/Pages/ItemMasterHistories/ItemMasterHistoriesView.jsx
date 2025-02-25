import { Head, useForm } from "@inertiajs/react";
import React, { useEffect } from "react";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import ContentPanel from "../../Components/Table/ContentPanel";
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from "../../Components/Table/Buttons/Button";
import InputComponent from "../../Components/Forms/Input";

const ItemMasterHistoriesView = ({
    page_title,
    item_master_approval,
    table_headers,
}) => {
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);

    return (
        <>
            <Head title={page_title} />
            <ContentPanel>
                <div className="border p-4 rounded-lg">
                    <form>
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
                                href="/item_master_histories"
                                extendClass={`${
                                    theme === "bg-skin-white"
                                        ? primayActiveColor
                                        : theme
                                }`}
                                fontColor={textColorActive}
                            >
                                <span>Back</span>
                            </Button>
                        </div>
                    </form>
                </div>
            </ContentPanel>
        </>
    );
};

export default ItemMasterHistoriesView;
