import React, { useEffect } from 'react'
import { useState } from 'react';
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import DropdownSelect from '../../Components/Dropdown/Dropdown'
import InputComponent from '../../Components/Forms/Input'
import TableButton from '../../Components/Table/Buttons/TableButton'
import { router } from '@inertiajs/react';

const BrandsFilter = ({all_brand_groups}) => {
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

    const [filters, setFilters] = useState({
        brand_code: "",
        brand_description: "",
        brand_groups_id: "",
        contact_name: "",
        contact_email: "",
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
        <div onSubmit={handleFilterSubmit} className='md:grid md:grid-cols-2 md:gap-2 space-y-2 md:space-y-0'>
            <DropdownSelect
                placeholder="Choose Status"
                selectType="react-select"
                defaultSelect="Select Status"
                onChange={(e) => handleFilter(e, "status", "select")}
                name="status"
                options={statuses}
                value={filters.status ? { label: filters.status, value: filters.status } : null}
            />
            <InputComponent
                name="brand_description"
                value={filters.brand_description}
                placeholder="Enter Brand Description"
                onChange={(e) => handleFilter(e, "brand_description")}
            />
            <InputComponent
                name="brand_code"
                value={filters.brand_code}
                placeholder="Enter Brand Code"
                onChange={(e) => handleFilter(e, "brand_code" , "text")}
            />
            <DropdownSelect
                placeholder="Choose Brand Group"
                selectType="react-select"
                defaultSelect="Select Brand Group"
                onChange={(e) => handleFilter(e, "brand_groups_id", "select")}
                name="brand_group"
                isStatus={true}
                options={all_brand_groups}
            />
            <InputComponent
                name="contact_name"
                value={filters.contact_name}
                placeholder="Enter Contact Name"
                onChange={(e) => handleFilter(e, "contact_name", "text")}
            />
            <InputComponent
                name="contact_email"
                value={filters.contact_email}
                placeholder="Enter Contact Email"
                onChange={(e) => handleFilter(e, "contact_email", "text")}
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

export default BrandsFilter