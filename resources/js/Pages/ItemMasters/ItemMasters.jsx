import { Head, Link, router, usePage } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import ContentPanel from "../../Components/Table/ContentPanel";
import TopPanel from "../../Components/Table/TopPanel";
import Tooltip from "../../Components/Tooltip/Tooltip";
import Button from "../../Components/Table/Buttons/Button";
import TableSearch from "../../Components/Table/TableSearch";
import TableContainer from "../../Components/Table/TableContainer";
import Thead from "../../Components/Table/Thead";
import Tbody from "../../Components/Table/Tbody";
import RowAction from '../../Components/Table/RowAction';
import Row from "../../Components/Table/Row";
import TableHeader from "../../Components/Table/TableHeader";
import RowData from "../../Components/Table/RowData";
import RowStatus from '../../Components/Table/RowStatus';
import Pagination from "../../Components/Table/Pagination";


const ItemMasters = ({page_title, tableName, item_masters, queryParams}) => {
    const {theme} = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);


    useEffect(() => {
        const segments = window.location.pathname.split("/");
        setPathname(segments.pop());
    }, []);

    const refreshTable = (e) => {
        e.preventDefault();
        router.get(pathname);
    };

    const handleModalClick = () => {
        setIsModalOpen(!isModalOpen);
    }

    return (
        <>
            <Head title={page_title}/>
            <ContentPanel>
            <TopPanel>
                    <div className="inline-flex gap-1">
                        <Tooltip text='Refresh data' arrow='bottom'>
                            <Button
                                extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                                fontColor={textColorActive}
                                onClick={refreshTable}
                            >
                                <i className='fa fa-table text-base p-[1px]'></i>
                            </Button>
                        </Tooltip>
                        <Button
                            extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                            type="button"
                            fontColor={textColorActive}
                            onClick={()=>{handleModalClick(); setAction('Add');
                                setUpdateData({
                                    id: "",
                                    brand_direction_description: "",
                                    status: "",
                                })
                            
                            }}
                        > 
                          <i className="fa-solid fa-plus mr-1"></i>  Add Item Master
                        </Button>
                    </div>
                    <div className='flex'>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={item_masters?.data}>
                   <Thead>
                        <Row>
                            <TableHeader
                                sortable={false}
                                width="md"
                                justify="center"
                            >
                                Action
                            </TableHeader>
                            <TableHeader
                                name="digits_code"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Digits Code
                            </TableHeader>
                            <TableHeader
                                name="upc_code"
                                queryParams={queryParams}
                                width="md"
                            >
                                UPC Code
                            </TableHeader>
                            <TableHeader
                                name="upc_code2"
                                queryParams={queryParams}
                                width="md"
                            >
                                UPC Code 2
                            </TableHeader>
                            <TableHeader
                                name="upc_code3"
                                queryParams={queryParams}
                                width="md"
                            >
                                UPC Code 3
                            </TableHeader>
                            <TableHeader
                                name="upc_code4"
                                queryParams={queryParams}
                                width="md"
                            >
                                UPC Code 4
                            </TableHeader>
                            <TableHeader
                                name="upc_code5"
                                queryParams={queryParams}
                                width="md"
                            >
                                UPC Code 5
                            </TableHeader>
                            <TableHeader
                                name="updated_by"
                                queryParams={queryParams}
                                width="md"
                            >
                                Updated By
                            </TableHeader>
                            <TableHeader
                                name="updated_by"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Created At
                            </TableHeader>
                            <TableHeader
                                name="updated_by"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Updated At
                            </TableHeader>
                        </Row>
                    </Thead>
                    <Tbody data={item_masters.data}>
                        {item_masters &&
                            item_masters?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center>
                                        <RowAction
                                            type="button"
                                            action="edit"
                                            onClick={()=>{handleModalClick(); setAction('Update'); 
                                                setUpdateData({
                                                    id: item.id,
                                                    brand_direction_description: item.brand_direction_description,
                                                    status: item.status,
                                                })
                                            
                                            }}
                                        />
                                        <RowAction
                                            type="button"
                                            action="view"
                                            onClick={()=>{handleModalClick(); setAction('View'); 
                                                setUpdateData({
                                                    id: item.id,
                                                    brand_direction_description: item.brand_direction_description,
                                                    status: item.status,
                                                })
                                            
                                            }}
                                        />
                                    </RowData>
                                    <RowStatus
                                        isLoading={loading}
                                        systemStatus={
                                            item.status === "ACTIVE"
                                                ? "active"
                                                : "inactive"
                                        }
                                    >
                                        {item.status === "ACTIVE"
                                            ? "ACTIVE"
                                            : "INACTIVE"}
                                    </RowStatus>
                                    <RowData isLoading={loading}>
                                        {item.brand_direction_description}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.get_created_by?.name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.get_updated_by?.name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.created_at}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.updated_at}
                                    </RowData>
                                </Row>
                            ))}
                    </Tbody>
                </TableContainer>
                <Pagination extendClass={theme} paginate={item_masters} />
            </ContentPanel>
    
        </>
    );
};

export default ItemMasters;
