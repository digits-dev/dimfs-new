import React, { useEffect } from 'react'
import { useState } from 'react';
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import DropdownSelect from '../../Components/Dropdown/Dropdown'
import InputComponent from '../../Components/Forms/Input'
import TableButton from '../../Components/Table/Buttons/TableButton'
import { router } from '@inertiajs/react';

const SubCategoriesFilter = ({all_categories}) => {
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

    const [filters, setFilters] = useState({
        categories_id: "",
        subcategory_code: "",
        subcategory_description: "",
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
                placeholder="Choose Category"
                selectType="react-select"
                defaultSelect="Select Category"
                onChange={(e) => handleFilter(e, "categories_id", "select")}
                name="category_name"
                isStatus={true}
                options={all_categories}
            />
            <InputComponent
                name="subcategory_code"
                displayName="Sub Category Code"
                value={filters.subcategory_code}
                placeholder="Enter Sub Category Code"
                onChange={(e) => handleFilter(e, "subcategory_code", "text")}
            />
            <InputComponent
                name="subcategory_description"
                displayName="Sub Category Description"
                value={filters.subcategory_description}
                placeholder="Enter Sub Category Description"
                onChange={(e) => handleFilter(e, "subcategory_description", "text")}
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

export default SubCategoriesFilter