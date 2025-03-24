import React from "react";
import DescIcon from "../Table/Icons/DescIcon";
import FormatLabelName from "../../Utilities/FormatLabelName";
import Select from 'react-select';
import { useTheme } from "../../Context/ThemeContext";
import LoginInputTooltip from "../Tooltip/LoginInputTooltip";

const CustomSelect = ({isMulti = false, onError, maxMenuHeight = "100px", isStatus = false, menuPlacement, isDisabled,  options, onChange, value, name, defaultSelect, displayName, is_multi='', selectType = 'react-select', placeholder, extendClass, addMainClass }) => {
    const {theme} = useTheme();
    const customStyles = {
        control: (provided) => ({
            ...provided,
            backgroundColor: "#101215", // Dark background (Tailwind's bg-gray-800)
            borderColor: onError ? '#dc3545' : "#9CA3AF)", // Border color (Tailwind's border-gray-600)
            color: "#fff", // Text color
            boxShadow: "none",
            "&:hover": {
                borderColor: "#9ca3af", // Hover state border color (Tailwind's border-gray-400)
            },
        }),
        singleValue: (provided) => ({
            ...provided,
            color: "#9CA3AF", // Ensure selected value text is white
        }),
        menu: (provided) => ({
            ...provided,
            backgroundColor: "#1f2937", // Dark background for dropdown menu
            color: "#9CA3AF", // Dropdown text color
            maxHeight: maxMenuHeight,
            zIndex: 9999,
            
        }),
        menuList: (provided) => ({
            ...provided,
            maxHeight: maxMenuHeight, 
            overflowY: "auto", 
            
        }),
        option: (provided, state) => ({
            ...provided,
            backgroundColor: state.isFocused ? "#374151" : "#1f2937", // Highlight on hover (Tailwind's bg-gray-700)
            color: isStatus ? state.data.status == "INACTIVE" ? "#EB4034" : "#9CA3AF" : "#9CA3AF", // Option text color
            "&:active": {
                backgroundColor: "#4b5563", // Active state background
            },
        }),
    };

    const NoDropdownIndicator = () => null;

    const customStatusStyles = {
        control: (provided) => ({
            ...provided,
            borderColor: onError ? '#dc3545' : "#9CA3AF)",
      
        }),
        option: (provided, state) => ({
          ...provided,
          color: isStatus ? state.data.status === "INACTIVE" ? "#EB4034" : "" : "", // Make text red for INACTIVE status
        }),
        menu: (provided) => ({
            ...provided,
            maxHeight: maxMenuHeight,
            zIndex: 9999,
        }),
        menuList: (provided) => ({
            ...provided,
            maxHeight: maxMenuHeight, 
            overflowY: "auto", 
        }),
      };
      
    return (
        <div className={`relative ${addMainClass}`}>
            <label
                htmlFor={name}
                className={`block text-xs font-bold ${theme === 'bg-skin-black' ? ' text-gray-400' : 'text-gray-700'}  font-poppins`}
            >
                {displayName || FormatLabelName(name)}
            </label>
            <div className="relative">
                <Select
                    placeholder={placeholder}
                    defaultValue={value}
                    name={name}
                    isMulti={isMulti}
                    components={onError ? { DropdownIndicator: NoDropdownIndicator } : undefined}
                    isDisabled={isDisabled}
                    className={`block w-full bg-gray-800 border-gray-300 mt-1  rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm`}
                    onChange={onChange}
                    menuPlacement={menuPlacement}
                    options={options.map(opt => ({ value: opt.value, label: opt.label, name: name, status: opt.status}))}
                    styles={theme === 'bg-skin-black' ? customStyles : customStatusStyles}
                />
                 {onError && 
                    <LoginInputTooltip content={onError}>
                    <i
                        className="fa-solid fa-circle-info text-red-600 absolute cursor-pointer top-1/2 text-xs md:text-base right-1.5 md:right-3 transform -translate-y-1/2">
                    </i>
                    </LoginInputTooltip>
                }
            </div>
        </div>
    );
};

export default CustomSelect;
