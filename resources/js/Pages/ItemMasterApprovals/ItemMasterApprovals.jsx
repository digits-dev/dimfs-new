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
import RowStatus from "../../Components/Table/RowStatus";
import Pagination from "../../Components/Table/Pagination";
import ApprovalBulkActions from "../../Components/Table/Buttons/ApprovalBulkActions";
import Checkbox from "../../Components/Checkbox/Checkbox";
import Export from "../../Components/Table/Buttons/Export";

const ItemMasterApprovals = ({
    page_title,
    tableName,
    item_master_approvals,
    queryParams,
    table_headers,
}) => {
    const { handleToast } = useToast();
    const { theme } = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive, buttonSwalColor } =
        useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

    useEffect(() => {
        const segments = window.location.pathname.split("/");
        setPathname(segments.pop());
    }, []);

    const refreshTable = (e) => {
        e.preventDefault();
        router.get(pathname);
    };

    // BULK ACTIONS

    const [isSelectAllChecked, setIsSelectAllChecked] = useState(false);

    const { data, setData, processing, reset, post, errors } = useForm({
        selectedIds: [],
        bulkAction: "",
    });

    const handleSelectAll = () => {
        if (isSelectAllChecked) {
            setData("selectedIds", []);
        } else {
            setData(
                "selectedIds",
                item_master_approvals.data.map((item) => item.id)
            );
        }
        setIsSelectAllChecked(!isSelectAllChecked);
    };

    const handleRowSelection = (id) => {
        setData((prevData) => {
            const selectedIds = prevData.selectedIds || [];
            let updatedSelectedIds;

            if (selectedIds.includes(id)) {
                updatedSelectedIds = selectedIds.filter((item) => item !== id);
            } else {
                updatedSelectedIds = [...selectedIds, id];
            }

            if (
                updatedSelectedIds.length === item_master_approvals.data.length
            ) {
                setIsSelectAllChecked(true);
            } else {
                setIsSelectAllChecked(false);
            }

            return { ...prevData, selectedIds: updatedSelectedIds };
        });
    };

    const handleBulkAction = () => {
        if (data.selectedIds.length === 0) {
            handleToast("No Data Selected", "error");
            return;
        }
        post("item_master_approvals/bulk_action", {
            onSuccess: (data) => {
                const { message, type } = data.props.auth.sessions;
                handleToast(message, type);
                reset();
            },
            onError: (error) => {},
        });

        setData("selectedIds", []);
        setIsSelectAllChecked(false);
    };

    const handleExport = () => {
        Swal.fire({
            title: `<p class="font-poppins text-3xl">Do you want to Export Pending Items?</p>`,
            showCancelButton: true,
            confirmButtonText: "Export",
            confirmButtonColor: buttonSwalColor,
            icon: "question",
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    window.location.href = `/item_master_approvals/export`;
                } catch (error) {
                    {
                        handleToast &&
                            handleToast(
                                "Something went wrong, please try again later.",
                                "Error"
                            );
                    }
                }
            }
        });
    };
    return (
        <>
            <Head title={page_title} />
            <ContentPanel>
                {table_headers.length != 0 ? (
                    <>
                        <TopPanel>
                            <div className="inline-flex gap-1">
                                <ApprovalBulkActions
                                    setData={setData}
                                    onConfirm={handleBulkAction}
                                />
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
                                <Button
                                    extendClass={
                                        (["bg-skin-white"].includes(theme)
                                            ? primayActiveColor
                                            : theme) + " py-[5px] px-[10px]"
                                    }
                                    type="button"
                                    fontColor={textColorActive}
                                    onClick={handleExport}
                                >
                                    <i className="fa-solid fa-download mr-1"></i>{" "}
                                    Export Pending Items
                                </Button>
                            </div>
                            <div className="flex">
                                <TableSearch queryParams={queryParams} />
                            </div>
                        </TopPanel>
                        <TableContainer data={item_master_approvals?.data}>
                            <Thead>
                                <Row>
                                    <TableHeader
                                        name="id"
                                        width="xm"
                                        sortable={false}
                                        justify="center"
                                    >
                                        <Checkbox
                                            handleClick={handleSelectAll}
                                            isChecked={isSelectAllChecked}
                                            disabled={false}
                                        />
                                    </TableHeader>
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

                            <Tbody data={item_master_approvals?.data}>
                                {item_master_approvals?.data?.map(
                                    (item, index) => (
                                        <Row key={index}>
                                            <RowData isLoading={loading} center>
                                                <Checkbox
                                                    handleClick={() =>
                                                        handleRowSelection(
                                                            item.id
                                                        )
                                                    }
                                                    isChecked={data.selectedIds.includes(
                                                        item.id
                                                    )}
                                                    disabled={false}
                                                />
                                            </RowData>
                                            <RowData center>
                                                {item.status ===
                                                    "FOR APPROVAL" && (
                                                    <RowAction
                                                        type="button"
                                                        action="edit"
                                                        onClick={() =>
                                                            router.get(
                                                                `/item_master_approvals/approval_view/approval/${item.id}`
                                                            )
                                                        }
                                                    />
                                                )}

                                                <RowAction
                                                    type="button"
                                                    action="view"
                                                    onClick={() =>
                                                        router.get(
                                                            `/item_master_approvals/approval_view/view/${item.id}`
                                                        )
                                                    }
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
