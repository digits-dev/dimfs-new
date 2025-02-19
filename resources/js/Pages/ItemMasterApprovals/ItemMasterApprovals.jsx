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

const ItemMasterApprovals = ({
    page_title,
    tableName,
    item_master_approvals,
    queryParams,
    table_headers,
}) => {
    const { theme } = useTheme();
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

    return (
        <>
            <Head title={page_title} />
            <ContentPanel>
                {table_headers.length != 0 ? (
                    <>
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
                                        <i className="fa fa-rotate-right text-base p-[1px]"></i>
                                    </Button>
                                </Tooltip>
                            </div>
                            <div className="flex">
                                <TableSearch queryParams={queryParams} />
                            </div>
                        </TopPanel>
                        <TableContainer data={item_master_approvals?.data}>
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
                                        width="md"
                                        queryParams={queryParams}
                                    >
                                        Status
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

                            <Tbody data={item_master_approvals?.data}>
                                {item_master_approvals?.data?.map(
                                    (item, index) => (
                                        <Row key={index}>
                                            <RowData center>
                                                {item.status ===
                                                    "FOR APPROVAL" && (
                                                    <RowAction
                                                        type="button"
                                                        action="edit"
                                                        onClick={() =>
                                                            router.get(
                                                                `/item_masters_approval/approval_view/${item.id}`
                                                            )
                                                        }
                                                    />
                                                )}

                                                <RowAction
                                                    type="button"
                                                    action="view"
                                                />
                                            </RowData>

                                            <RowStatus
                                                isLoading={loading}
                                                systemStatus={
                                                    item.status ===
                                                    "FOR APPROVAL"
                                                        ? "yellow"
                                                        : item.status ===
                                                          "APPROVED"
                                                        ? "green"
                                                        : "red"
                                                }
                                            >
                                                {item.status}
                                            </RowStatus>
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
                            paginate={item_master_approvals}
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

export default ItemMasterApprovals;
