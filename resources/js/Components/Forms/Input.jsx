import React, { useState } from "react";
import FormatLabelName from "../../Utilities/FormatLabelName";
import { useTheme } from "../../Context/ThemeContext";

const InputComponent = ({
    type = "text",
    name,
    value,
    onChange,
    placeholder,
    displayName,
    checked,
    disabled,
    addClass,
    inputClass
}) => {
    const {theme} = useTheme();
    return (
        <div className={addClass}>
            <label
                htmlFor={name}
                className={`block text-sm font-bold ${theme === 'bg-skin-black' ? ' text-gray-400' : 'text-gray-700'}  font-poppins`}
            >
                {displayName || FormatLabelName(name)}
            </label>
            <input
                id={name}
                type={type}
                value={value}
                name={name}
                disabled={disabled}
                onChange={onChange}
                placeholder={placeholder}
                className={`${theme === 'bg-skin-black' ? theme+' text-gray-300 disabled:bg-skin-black' : 'bg-white'} mt-1 block w-full px-3 py-2 border disabled:bg-gray-100 placeholder:text-sm placeholder:text-gray-400 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm ${inputClass}`}
                checked={checked}
                style={type === "date" ? { padding: "7px", fontSize: "14px" } : {}}
            />
        </div>
    );
};

export default InputComponent;
