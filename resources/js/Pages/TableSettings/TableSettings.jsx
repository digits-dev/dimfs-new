import { Head, Link, router, usePage } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import { useTheme } from "../../Context/ThemeContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import ContentPanel from "../../Components/Table/ContentPanel";
import TopPanel from "../../Components/Table/TopPanel";
import Tooltip from "../../Components/Tooltip/Tooltip";
import Button from "../../Components/Table/Buttons/Button";
import TableSearch from "../../Components/Table/TableSearch";
import TableContainer from "../../Components/Table/TableContainer";
import Thead from "../../Components/Table/Thead";
import Tbody from "../../Components/Table/Tbody";
import RowAction from "../../Components/Table/RowAction";
import Row from "../../Components/Table/Row";
import TableHeader from "../../Components/Table/TableHeader";
import RowData from "../../Components/Table/RowData";
import RowStatus from "../../Components/Table/RowStatus";
import Pagination from "../../Components/Table/Pagination";

const TableSettings = ({ tableName, table_settings, queryParams }) => {
    const { theme } = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

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
            <Head title="Table Settings" />
            <ContentPanel>
                <TopPanel>
                    <div className="inline-flex gap-1">
                        <Tooltip text="Refresh data" arrow="bottom">
                            <Button
                                extendClass={
                                    (["bg-skin-white"].includes(theme)
                                        ? primayActiveColor
                                        : theme) + " py-[5px] px-[10px]"
                                }
                                fontColor={textColorActive}
                                onClick={refreshTable}
                            >
                                <i className="fa fa-table text-base p-[1px]"></i>
                            </Button>
                        </Tooltip>

                        {/* Updated Button to Navigate to Create Page */}
                        <Button
                            extendClass={
                                (["bg-skin-white"].includes(theme)
                                    ? primayActiveColor
                                    : theme) + " py-[5px] px-[10px]"
                            }
                            type="button"
                            fontColor={textColorActive}
                            onClick={() =>
                                router.get("/table_settings/create_view")
                            }
                        >
                            <i className="fa-solid fa-plus mr-1"></i> Add Table
                            Setting
                        </Button>
                    </div>
                    <div className="flex">
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={table_settings?.data}>
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
                                name="status"
                                queryParams={queryParams}
                                width="sm"
                            >
                                Status
                            </TableHeader>
                            <TableHeader
                                name="adm_privileges_id"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Privilege Name
                            </TableHeader>
                            <TableHeader
                                name="adm_moduls_id"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Module Name
                            </TableHeader>

                            <TableHeader
                                name="action_types_id"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Action Types ID
                            </TableHeader>
                            <TableHeader
                                name="report_header"
                                queryParams={queryParams}
                                width="2xl"
                            >
                                Report Header
                            </TableHeader>
                            <TableHeader
                                name="created_by"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Created By
                            </TableHeader>
                            <TableHeader
                                name="updated_by"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Updated By
                            </TableHeader>
                            <TableHeader
                                name="created_at"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Created At
                            </TableHeader>
                            <TableHeader
                                name="updated_at"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Updated At
                            </TableHeader>
                        </Row>
                    </Thead>
                    <Tbody data={table_settings?.data}>
                        {table_settings &&
                            table_settings?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center addClass="align-top">
                                        <div className="mt-0.5">
                                            <RowAction
                                                type="button"
                                                action="edit"
                                                onClick={() =>
                                                    router.get(
                                                        `/table_settings/edit_view/${item.id}`
                                                    )
                                                }
                                            />
                                        </div>
                                    </RowData>
                                    <RowStatus
                                        isLoading={loading}
                                        systemStatus={
                                            item.status === "ACTIVE"
                                                ? "active"
                                                : "inactive"
                                        }
                                        addClass="align-top"
                                        addStatusClass="mt-1"
                                    >
                                        {item.status === "ACTIVE"
                                            ? "ACTIVE"
                                            : "INACTIVE"}
                                    </RowStatus>
                                    <RowData isLoading={loading} addClass="align-top">
                                        <div className="mt-2">
                                            {item.get_privilege_name?.name}
                                        </div>
                                    </RowData>
                                    <RowData isLoading={loading} addClass="align-top">
                                        <div className="mt-2">
                                            {item.get_module_name?.name}
                                        </div>
                                    </RowData>
                                    <RowData isLoading={loading} addClass="align-top">
                                        <div className="mt-2">
                                            { item.get_action_types.action_type_description }
                                        </div>
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        <div className="flex flex-wrap gap-1">
                                            {item.report_header
                                                ?.split(",")
                                                .map((header, index) => (
                                                    <p
                                                        key={index}
                                                        className={`bg-cyan-400 text-xs text-white px-2  py-1 rounded-md font-semibold`}
                                                    >
                                                        {header}
                                                    </p>
                                            ))}
                                        </div>
                                        
                                    </RowData>
                                    <RowData isLoading={loading} addClass="align-top">
                                        <div className="mt-2">
                                            {item.get_created_by?.name}
                                        </div>  
                                    </RowData>
                                    <RowData isLoading={loading} addClass="align-top">
                                        <div className="mt-2">
                                            {item.get_updated_by?.name}
                                        </div>  
                                     
                                    </RowData>
                                    <RowData isLoading={loading} addClass="align-top">
                                        <div className="mt-2">
                                            {item.created_at}
                                        </div>  
                                     
                                    </RowData>
                                    <RowData isLoading={loading} addClass="align-top">
                                        <div className="mt-2">
                                            {item.updated_at}
                                        </div>  
                                       
                                    </RowData>
                                </Row>
                            ))}
                    </Tbody>
                </TableContainer>
                <Pagination extendClass={theme} paginate={table_settings} />
            </ContentPanel>
        </>
    );
};

export default TableSettings;
