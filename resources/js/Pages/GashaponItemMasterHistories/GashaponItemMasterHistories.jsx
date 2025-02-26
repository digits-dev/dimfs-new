import { Head, Link, router, useForm, usePage } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
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
import Pagination from "../../Components/Table/Pagination";

const GashaponItemMasterHistories = ({
    page_title,
    tableName,
    gashapon_item_master_histories,
    queryParams,
    table_headers,
}) => {
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
            <Head title={page_title} />
            <ContentPanel>
                {table_headers.length != 0 ? (
                    <>
                        <TopPanel>
                            <div className="inline-flex gap-1">
                                <Tooltip text="Refresh data" arrow="top">
                                    <Button
                                        extendClass={
                                            (["bg-skin-white"].includes(theme)
                                                ? primayActiveColor
                                                : theme) + " py-[5px] px-[10px]"
                                        }
                                        fontColor={textColorActive}
                                        onClick={refreshTable}
                                    >
                                        <i className="fa fa-rotate-right text-base p-[1px]"></i>
                                    </Button>
                                </Tooltip>
                            </div>
                            <div className="flex">
                                <TableSearch queryParams={queryParams} />
                            </div>
                        </TopPanel>
                        <TableContainer
                            data={gashapon_item_master_histories?.data}
                        >
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
                                        width="md"
                                        justify="center"
                                        queryParams={queryParams}
                                    >
                                        Method
                                    </TableHeader>
                                    {table_headers &&
                                        table_headers?.map((header, index) => (
                                            <TableHeader
                                                key={index}
                                                sortable={false}
                                                name={header.name}
                                                queryParams={queryParams}
                                                width={header.width}
                                            >
                                                {header.header_name}
                                            </TableHeader>
                                        ))}
                                    <TableHeader
                                        name="created_by"
                                        queryParams={queryParams}
                                        width="md"
                                    >
                                        Approved By
                                    </TableHeader>
                                    <TableHeader
                                        name="updated_by"
                                        queryParams={queryParams}
                                        width="lg"
                                    >
                                        Approved At
                                    </TableHeader>{" "}
                                    <TableHeader
                                        name="created_by"
                                        queryParams={queryParams}
                                        width="md"
                                    >
                                        Rejected By
                                    </TableHeader>
                                    <TableHeader
                                        name="updated_by"
                                        queryParams={queryParams}
                                        width="lg"
                                    >
                                        Rejected At
                                    </TableHeader>{" "}
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
                                        width="lg"
                                    >
                                        Created At
                                    </TableHeader>
                                </Row>
                            </Thead>

                            <Tbody data={gashapon_item_master_histories?.data}>
                                {gashapon_item_master_histories?.data?.map(
                                    (item, index) => (
                                        <Row key={index}>
                                            <RowData center>
                                                <RowAction
                                                    type="button"
                                                    action="view"
                                                    onClick={() =>
                                                        router.get(
                                                            `/gashapon_item_master_histories/view/${item.id}`
                                                        )
                                                    }
                                                />
                                            </RowData>

                                            <RowData isLoading={loading}>
                                                {item.action}
                                            </RowData>
                                            {table_headers.map(
                                                (header, idx) => (
                                                    <RowData
                                                        key={idx}
                                                        isLoading={loading}
                                                    >
                                                        {item.item_values?.[
                                                            header.name
                                                        ] ?? null}
                                                    </RowData>
                                                )
                                            )}
                                            <RowData isLoading={loading}>
                                                {item.get_approved_by?.name}
                                            </RowData>

                                            <RowData isLoading={loading}>
                                                {item.approved_at}
                                            </RowData>
                                            <RowData isLoading={loading}>
                                                {item.get_rejected_by?.name}
                                            </RowData>

                                            <RowData isLoading={loading}>
                                                {item.rejected_at}
                                            </RowData>
                                            <RowData isLoading={loading}>
                                                {item.get_created_by?.name}
                                            </RowData>
                                            <RowData isLoading={loading}>
                                                {item.created_at}
                                            </RowData>
                                        </Row>
                                    )
                                )}
                            </Tbody>
                        </TableContainer>
                        <Pagination
                            extendClass={theme}
                            paginate={gashapon_item_master_histories}
                        />
                    </>
                ) : (
                    <div className="flex flex-col items-center justify-center select-none">
                        <img
                            src="/images/others/403-logo.png"
                            className="w-[800px]"
                        />
                        <Link
                            href="/dashboard"
                            className="my-[20px] bg-blue-950 py-3 px-5 rounded-[50px] text-white font-poppins hover:opacity-70"
                        >
                            Go to Dashboard
                        </Link>
                    </div>
                )}
            </ContentPanel>
        </>
    );
};

export default GashaponItemMasterHistories;
