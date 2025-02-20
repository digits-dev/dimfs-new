import React, { useEffect } from 'react'
import { useState } from 'react';
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import DropdownSelect from '../../Components/Dropdown/Dropdown'
import InputComponent from '../../Components/Forms/Input'
import TableButton from '../../Components/Table/Buttons/TableButton'
import { router } from '@inertiajs/react';

const RmaStoreCategoriesFilter = ({all_rma_sub_classifications}) => {
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

    const [filters, setFilters] = useState({
        rma_sub_classifications_id: "",
        store_category_description: "",
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
                placeholder="Choose RMA Sub Classification"
                selectType="react-select"
                defaultSelect="Select RMA Sub Classification"
                onChange={(e) => handleFilter(e, "rma_sub_classifications_id", "select")}
                name="rma_sub_classifications_name"
                displayName="RMA Sub Classification"
                isStatus={true}
                options={all_rma_sub_classifications}
            />
            <InputComponent
                name="store_category_description"
                value={filters.store_category_description}
                displayName="RMA Store Category Description"
                placeholder="Enter RMA Store Category Description"
                onChange={(e) => handleFilter(e, "store_category_description", "text")}
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

export default RmaStoreCategoriesFilter