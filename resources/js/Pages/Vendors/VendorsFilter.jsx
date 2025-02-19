import React, { useEffect } from 'react'
import { useState } from 'react';
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import DropdownSelect from '../../Components/Dropdown/Dropdown'
import InputComponent from '../../Components/Forms/Input'
import TableButton from '../../Components/Table/Buttons/TableButton'
import { router } from '@inertiajs/react';

const VendorsFilter = ({all_brands, all_vendor_types, all_incoterms}) => {
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

    const [filters, setFilters] = useState({
        brands_id: "",
        vendor_types_id: "",
        incoterms_id: "",
        vendor_name: "",
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
        <div onSubmit={handleFilterSubmit} className='flex flex-col space-y-2 md:space-y-2'>
            <DropdownSelect
                placeholder="Choose Status"
                selectType="react-select"
                defaultSelect="Select Status"
                onChange={(e) => handleFilter(e, "status", "select")}
                name="status"
                options={statuses}
                value={filters.status ? { label: filters.status, value: filters.status } : null}
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
                placeholder="Choose Vendor Type"
                selectType="react-select"
                defaultSelect="Select Vendor Type"
                onChange={(e) => handleFilter(e, "vendor_types_id", "select")}
                name="vendor_type"
                isStatus={true}
                options={all_vendor_types}
            />
            <DropdownSelect
                placeholder="Choose Incoterm"
                selectType="react-select"
                defaultSelect="Select Incoterm"
                onChange={(e) => handleFilter(e, "incoterms_id", "select")}
                name="incoterm_description"
                isStatus={true}
                options={all_incoterms}
            />
            <InputComponent
                name="vendor_name"
                value={filters.vendor_name}
                placeholder="Enter Vendor Name"
                onChange={(e) => handleFilter(e, "vendor_name", "text")}
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

export default VendorsFilter