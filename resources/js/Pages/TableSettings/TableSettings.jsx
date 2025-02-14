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
                                Adm Privileges ID
                            </TableHeader>
                            <TableHeader
                                name="adm_moduls_id"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Adm Moduls ID
                            </TableHeader>
                            <TableHeader
                                name="action_types_id"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Action Types ID
                            </TableHeader>
                            <TableHeader
                                name="table_name"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Table Name
                            </TableHeader>
                            <TableHeader
                                name="report_header"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Report Header
                            </TableHeader>
                            <TableHeader
                                name="created_by"
                                queryParams={queryParams}
                                width="md"
                            >
                                Created By
                            </TableHeader>
                            <TableHeader
                                name="updated_by"
                                queryParams={queryParams}
                                width="md"
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
                                    <RowData center>
                                        <RowAction
                                            type="button"
                                            action="edit"
                                            onClick={() =>
                                                router.get(
                                                    `/table_settings/edit_view/${item.id}`
                                                )
                                            }
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
                                        {item.get_privilege_name?.name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.get_module_name?.name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {
                                            item.get_action_types
                                                .action_type_description
                                        }
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.table_name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.report_header
                                            ?.split(",")
                                            .map((header, index) => (
                                                <span
                                                    key={index}
                                                    className="bg-blue-200 text-black px-2 py-1 rounded-lg font-semibold mr-1"
                                                >
                                                    {header}
                                                </span>
                                            ))}
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
                <Pagination extendClass={theme} paginate={table_settings} />
            </ContentPanel>
        </>
    );
};

export default TableSettings;
