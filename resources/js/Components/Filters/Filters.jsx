import React, { useEffect } from "react";
import { useState } from "react";
import { useTheme } from "../../Context/ThemeContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import TableButton from "../Table/Buttons/TableButton";
import { router } from "@inertiajs/react";
import MultiTypeInput from "../Forms/MultiTypeInput";

const Filters = ({ filter_inputs }) => {
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    console.log(filter_inputs);
    const initialFormData = filter_inputs.reduce((acc, item) => {
        acc[item.name] = "";
        return acc;
    }, {});

    console.log(filter_inputs);

    const [filters, setFilters] = useState(initialFormData);

    useEffect(() => {
        const segments = window.location.pathname.split("/");
        setPathname(segments.pop());
    }, []);

    const handleFilter = (e, attrName, type) => {
        if (type === "select") {
            const { value } = e;

            setFilters((filters) => ({
                ...filters,
                [attrName]: value,
            }));
        } else {
            const { name, value } = e.target;

            setFilters((filters) => ({
                ...filters,
                [name]: value,
            }));
        }
    };

    const handleFilterSubmit = (e) => {
        e.preventDefault();
        const queryString = new URLSearchParams(filters).toString();
        router.get(`${pathname}?${queryString}`);
    };

    return (
        <form>
            <div
                onSubmit={handleFilterSubmit}
                className="md:grid md:grid-cols-2 md:gap-2 space-y-2 md:space-y-0"
            >
                {filter_inputs.map((input, index) => (
                    <div key={index} className="w-full">
                        <MultiTypeInput
                            name={input.name}
                            type={input.type}
                            onChange={(selectedValue) =>
                                handleFilter(
                                    selectedValue,
                                    input.name,
                                    input.type
                                )
                            }
                            displayName={input.header_name}
                            placeholder={`Enter ${input.header_name}`}
                            menuPlacement={
                                index === filter_inputs.length - 1
                                    ? "top"
                                    : "auto"
                            }
                            selectInputOptions={
                                input.table_data ? input.table_data : []
                            }
                        />
                    </div>
                ))}
            </div>
            <div className="mt-5 flex justify-end">
                <TableButton
                    extendClass={
                        ["bg-skin-white"].includes(theme)
                            ? primayActiveColor
                            : theme
                    }
                    fontColor={textColorActive}
                    onClick={handleFilterSubmit}
                >
                    <i className="fa fa-filter"></i> Filter
                </TableButton>
            </div>
        </form>
    );
};

export default Filters;
