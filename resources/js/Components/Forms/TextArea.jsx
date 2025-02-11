import React, { useState } from "react";
import FormatLabelName from "../../Utilities/FormatLabelName";
import { useTheme } from "../../Context/ThemeContext";

const TextArea = ({
    type = "text",
    name,
    value,
    onChange,
    placeholder,
    displayName,
    checked,
    disabled,
    addClass,
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
            <textarea
                id={name}
                type={type}
                value={value}
                name={name}
                disabled={disabled}
                onChange={onChange}
                placeholder={placeholder}
                className={`${theme === 'bg-skin-black' ? theme+' text-gray-300' : 'bg-white'} mt-1 block w-full px-3 py-2 border placeholder:text-sm placeholder:text-gray-600 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm`}
                checked={checked}
            />
        </div>
    );
};

export default TextArea;
