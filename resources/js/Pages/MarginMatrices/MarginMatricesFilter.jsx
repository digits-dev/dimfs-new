import React, { useEffect } from 'react'
import { useState } from 'react';
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import DropdownSelect from '../../Components/Dropdown/Dropdown'
import InputComponent from '../../Components/Forms/Input'
import TableButton from '../../Components/Table/Buttons/TableButton'
import { router } from '@inertiajs/react';

const MarginMatricesFilter = ({all_brands, all_vendor_types}) => {
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

    const [filters, setFilters] = useState({
        brands_id: "",
        margin_category: "",
        max: "",
        min: "",
        store_margin_percentage: "",
        matrix_type: "",
        vendor_types_id: "",
        status: "",
    });

    const statuses = [
        {
            id: 'ACTIVE',
            name:'ACTIVE',
        },
        {
            id: 'INACTIVE',
            name:'INACTIVE',
        },
    ]


    const types = [
        {
            id: 'ADD TO LC',
            name:'ADD TO LC',
        },
        {
            id: 'BASED ON MATRIX',
            name:'BASED ON MATRIX',
        },
        {
            id: 'DEDUCT FROM MALC',
            name:'DEDUCT FROM MALC',
        },
    ]

    const marginCategory = [
        {
            id: 'ACCESSORIES',
            name:'ACCESSORIES',
        },
        {
            id: 'UNIT ACCESSORIES',
            name:'UNIT ACCESSORIES',
        },
        {
            id: 'UNITS',
            name:'UNITS',
        },
    ]

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
        <div onSubmit={handleFilterSubmit} className='md:grid md:grid-cols-2 md:gap-2 space-y-2 md:space-y-0'>
            <DropdownSelect
                placeholder="Choose Status"
                selectType="react-select"
                defaultSelect="Select Status"
                onChange={(e) => handleFilter(e, "status", "select")}
                name="status"
                options={statuses}
            />
            <DropdownSelect
                placeholder="Choose Brand"
                selectType="react-select"
                defaultSelect="Select Brand"
                onChange={(e) => handleFilter(e, "brands_id", "select")}
                name="brand"
                isStatus={true}
                options={all_brands}
            />
            <DropdownSelect
                placeholder="Choose Margin Category"
                selectType="react-select"
                defaultSelect="Select Margin Category"
                onChange={(e) => handleFilter(e, "margin_category", "select")}
                name="margin_category"
                isStatus={true}
                options={marginCategory}
            />
            <InputComponent
                name="max"
                value={filters.max}
                placeholder="Enter Max"
                onChange={(e) => handleFilter(e, "max")}
            />
            <InputComponent
                name="min"
                value={filters.min}
                placeholder="Enter Min"
                onChange={(e) => handleFilter(e, "min" , "text")}
            />
            <InputComponent
                name="store_margin_percentage"
                value={filters.store_margin_percentage}
                displayName="Store Margin (%)"
                placeholder="Enter Store Margin (%)"
                onChange={(e) => handleFilter(e, "store_margin_percentage", "text")}
            />
            <DropdownSelect
                placeholder="Choose Type"
                selectType="react-select"
                defaultSelect="Select Type"
                menuPlacement="top"
                onChange={(e) => handleFilter(e, "matrix_type", "select")}
                name="type"
                options={types}
            />
            <DropdownSelect
                placeholder="Choose Vendor Type"
                selectType="react-select"
                defaultSelect="Select Vendor Type"
                menuPlacement="top"
                onChange={(e) => handleFilter(e, "vendor_types_id", "select")}
                name="vendor_type"
                options={all_vendor_types}
            />
        </div>
        <div className='mt-5 flex justify-end'>
            <TableButton 
                extendClass={["bg-skin-white"].includes(theme)? primayActiveColor : theme} 
                fontColor={textColorActive}
                onClick={handleFilterSubmit}
            > 
                <i className="fa fa-filter"></i> Filter
            </TableButton>
        </div>
    </form>
    
  )
}

export default MarginMatricesFilter