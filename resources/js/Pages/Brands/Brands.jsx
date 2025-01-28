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

const Brands = ({tableName, brands, queryParams}) => {
    const {theme} = useTheme();
    const [loading, setLoading] = useState(false);
    const { textColor, primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);

     useEffect(() => {
        const segments = window.location.pathname.split("/");
        setPathname(segments.pop());
    }, []);

     const refreshTable = (e) => {
        e.preventDefault();
        router.get(pathname);
    };
    return (
        <>
            <Head title="Brands"/>
            <ContentPanel>
            <TopPanel>
                    <div className="inline-flex gap-1">
                        <Tooltip text='Refresh data' arrow='bottom'>
                            <Button
                                href="privileges/create-privileges"
                                extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                                fontColor={textColorActive}
                                onClick={refreshTable}
                            >
                                <i className='fa fa-table text-base p-[1px]'></i>
                            </Button>
                        </Tooltip>
                        <Button
                            href="privileges/create-privileges"
                            extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                            type="button"
                            fontColor={textColorActive}
                        >
                            <i className="fa fa-plus-circle mr-1"></i> Add
                            Brand
                        </Button>
                    </div>
                    <div className='flex'>
                        
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer>
                   <Thead>
                        <Row>
                            <TableHeader
                                name="id"
                                queryParams={queryParams}
                                width="sm"
                            >
                                ID
                            </TableHeader>
                            <TableHeader
                                name="brand_code"
                                queryParams={queryParams}
                                width="sm"
                            >
                                Brand Code
                            </TableHeader>
                            <TableHeader
                                name="brand_description"
                                queryParams={queryParams}
                                width="sm"
                            >
                                Brand Description
                            </TableHeader>
                            <TableHeader
                                name="brand_group"
                                queryParams={queryParams}
                                width="sm"
                            >
                                Brand Group
                            </TableHeader>
                            <TableHeader
                                name="contact_name"
                                queryParams={queryParams}
                                width="sm"
                            >
                                Contact Name
                            </TableHeader>
                            <TableHeader
                                name="contact_email"
                                queryParams={queryParams}
                                width="sm"
                            >
                                Contact Email
                            </TableHeader>
                            <TableHeader
                                name="status"
                                queryParams={queryParams}
                                width="sm"
                            >
                                Status
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="auto"
                                justify="center"
                            >
                                Action
                            </TableHeader>
                        </Row>
                    </Thead>
                    <Tbody data={brands?.data}>
                        {brands &&
                            brands?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData isLoading={loading}>
                                        {item.id}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.brand_code}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.brand_description}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.brand_group}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.contact_name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.contact_email}
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
                                    <RowData center>
                                        <RowAction
                                            as="button"
                                            action="edit"
                                        />
                                        <RowAction
                                            as="button"
                                            action="view"
                                        />
                                    </RowData>
                                </Row>
                            ))}
                    </Tbody>
                </TableContainer>
                <Pagination extendClass={theme} paginate={brands} />
            </ContentPanel>
        </>
    );
};

export default Brands;
