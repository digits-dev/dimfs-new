import React, { useEffect } from 'react'
import { useState } from 'react';
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import DropdownSelect from '../../Components/Dropdown/Dropdown'
import InputComponent from '../../Components/Forms/Input'
import TableButton from '../../Components/Table/Buttons/TableButton'
import { router } from '@inertiajs/react';

const ItemMasterAccountingApprovalsFilter = ({}) => {
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

    const [filters, setFilters] = useState({
        status: "",
        item_masters_id: "",
        brands_id: "",
        categories_id: "",
        margin_categories_id: "",
        store_cost: "",
        store_cost_percentage: "",
        ecom_store_cost: "",
        ecom_store_cost_percentage: "",
        landed_cost: "",
        landed_cost_sea: "",
        actual_landed_cost: "",
        working_store_cost: "",
        working_store_cost_percentage: "",
        ecom_working_store_cost: "",
        ecom_working_store_cost_percentage: "",
        working_landed_cost: "",
        effective_date: "",
        duration_from: "",
        duration_to: "",
        support_types_id: "",
    });

    const statuses = [
        {
            id: 'FOR APPROVAL',
            name:'FOR APPROVAL',
        },
        {
            id: 'APPROVED',
            name:'APPROVED',
        },
        {
            id: 'APPROVED - SCHEDULED',
            name:'APPROVED - SCHEDULED',
        },
        {
            id: 'REJECTED',
            name:'REJECTED',
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
                name="item_masters_id"
                displayName="Digits Code"
                value={filters.item_masters_id}
                placeholder="Enter Digits Code"
                onChange={(e) => handleFilter(e, "item_masters_id")}
            />
            <InputComponent
                name="brands_id"
                displayName="Brand Description"
                value={filters.brands_id}
                placeholder="Enter Brand Description"
                onChange={(e) => handleFilter(e, "brands_id")}
            />
            <InputComponent
                name="categories_id"
                displayName="Category Description"
                value={filters.categories_id}
                placeholder="Enter Brand Description"
                onChange={(e) => handleFilter(e, "categories_id")}
            />
            <InputComponent
                name="margin_categories_id"
                displayName="Margin Category Description"
                value={filters.margin_categories_id}
                placeholder="Enter Margin Category Description"
                onChange={(e) => handleFilter(e, "margin_categories_id")}
            />
            <InputComponent
                name="store_cost"
                value={filters.store_cost}
                placeholder="Enter Store Cost"
                onChange={(e) => handleFilter(e, "store_cost")}
            />
            <InputComponent
                name="store_cost_percentage"
                value={filters.store_cost_percentage}
                displayName="Store Margin (%)"
                placeholder="Enter Store Margin (%)"
                onChange={(e) => handleFilter(e, "store_cost_percentage")}
            />
            <InputComponent
                name="ecom_store_cost"
                value={filters.store_cost_percentage}
                displayName="ECOMM - Store Cost"
                placeholder="Enter ECOMM - Store Cost"
                onChange={(e) => handleFilter(e, "store_cost_percentage")}
            />
            <InputComponent
                name="ecom_store_cost_percentage"
                value={filters.store_cost_percentage}
                displayName="ECOMM - Store Margin (%)"
                placeholder="Enter ECOMM - Store Margin (%)"
                onChange={(e) => handleFilter(e, "store_cost_percentage")}
            />
            <InputComponent
                name="landed_cost"
                value={filters.landed_cost}
                placeholder="Enter Landed Cost"
                onChange={(e) => handleFilter(e, "landed_cost")}
            />
            <InputComponent
                name="landed_cost_sea"
                value={filters.landed_cost_sea}
                placeholder="Enter Landed Cost via SEA"
                onChange={(e) => handleFilter(e, "landed_cost_sea")}
            />
            <InputComponent
                name="actual_landed_cost"
                value={filters.actual_landed_cost}
                placeholder="Enter Actual Landed Cost"
                onChange={(e) => handleFilter(e, "actual_landed_cost")}
            />
            <InputComponent
                name="working_store_cost"
                value={filters.working_store_cost}
                placeholder="Enter Working Store Cost"
                onChange={(e) => handleFilter(e, "working_store_cost")}
            />
            <InputComponent
                name="working_store_cost_percentage"
                displayName="Working Store Margin (%)"
                value={filters.working_store_cost_percentage}
                placeholder="Enter Working Store Margin (%)"
                onChange={(e) => handleFilter(e, "working_store_cost_percentage")}
            />
            <InputComponent
                name="ecom_working_store_cost"
                value={filters.ecom_working_store_cost}
                placeholder="Enter ECOMM - Working Store Cost"
                onChange={(e) => handleFilter(e, "ecom_working_store_cost")}
            />
            <InputComponent
                name="ecom_working_store_cost_percentage"
                value={filters.ecom_working_store_cost_percentage}
                displayName="ECOMM - Working Store Margin (%)"
                placeholder="Enter ECOMM - Working Store Margin (%)"
                onChange={(e) => handleFilter(e, "ecom_working_store_cost_percentage")}
            />
            <InputComponent
                name="working_landed_cost"
                value={filters.working_landed_cost}
                placeholder="Enter Working Landed Cost"
                onChange={(e) => handleFilter(e, "working_landed_cost")}
            />
            <InputComponent
                type='date'
                name="effective_date"
                value={filters.effective_date}
                placeholder="Enter Working Landed Cost"
                onChange={(e) => handleFilter(e, "effective_date")}
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

export default ItemMasterAccountingApprovalsFilter