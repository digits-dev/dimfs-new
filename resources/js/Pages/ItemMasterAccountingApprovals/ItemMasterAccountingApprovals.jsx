import { Head, Link, router, useForm, usePage } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import ContentPanel from "../../Components/Table/ContentPanel";
import Tooltip from "../../Components/Tooltip/Tooltip";
import Button from "../../Components/Table/Buttons/Button";
import TopPanel from "../../Components/Table/TopPanel";
import Export from "../../Components/Table/Buttons/Export";
import CustomFilter from "../../Components/Table/Buttons/CustomFilter";
import TableSearch from "../../Components/Table/TableSearch";
import { useTheme } from "../../Context/ThemeContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import TableContainer from "../../Components/Table/TableContainer";
import Thead from "../../Components/Table/Thead";
import Row from "../../Components/Table/Row";
import TableHeader from "../../Components/Table/TableHeader";
import Tbody from "../../Components/Table/Tbody";
import RowData from "../../Components/Table/RowData";
import RowAction from "../../Components/Table/RowAction";
import RowStatus from "../../Components/Table/RowStatus";
import Pagination from "../../Components/Table/Pagination";
import Checkbox from "../../Components/Checkbox/Checkbox";
import ApprovalBulkActions from "../../Components/Table/Buttons/ApprovalBulkActions";
import { useToast } from "../../Context/ToastContext";
import ItemMasterAccountingApprovalsFilter from "./ItemMasterAccountingApprovalsFilter";

const ItemMasterAccountingApprovals = ({page_title, queryParams, item_accounting_approvals}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { textColorActive } = useThemeStyles(theme);
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
                item_accounting_approvals.data.map((item) => item.id)
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
                updatedSelectedIds.length === item_accounting_approvals.data.length
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
            handleToast("No Item/s Selected", "error");
            return;
        }
        post("item_master_accounting_approvals/bulk_action", {
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


    return (
        <>
            <Head title={page_title}/>
            <ContentPanel>
            <TopPanel>
                    <div className="inline-flex gap-1">
                        <ApprovalBulkActions
                            setData={setData}
                            onConfirm={handleBulkAction}
                        />
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
                        <Export path="/item_master_accounting_approvals/export" page_title={page_title}/>
                    </div>
                    <div className="flex">
                        <CustomFilter>
                            <ItemMasterAccountingApprovalsFilter/>
                        </CustomFilter>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={item_accounting_approvals?.data}>
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
                                queryParams={queryParams}
                                width="lg"
                            >
                                Approval Status
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                queryParams={queryParams}
                                width="lg"
                            >
                                Digits Code
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                queryParams={queryParams}
                                width="lg"
                            >
                                Brand Description
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                queryParams={queryParams}
                                width="lg"
                            >
                                Category Description
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                queryParams={queryParams}
                                width="xl"
                            >
                                Margin Category Description
                            </TableHeader>
                            <TableHeader
                                name="store_cost"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Store Cost
                            </TableHeader>
                            <TableHeader
                                name="store_cost_percentage"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Store Margin (%)
                            </TableHeader>
                            <TableHeader
                                name="ecom_store_cost"
                                queryParams={queryParams}
                                width="lg"
                            >
                                ECOMM - Store Cost
                            </TableHeader>
                            <TableHeader
                                name="ecom_store_cost_percentage"
                                queryParams={queryParams}
                                width="lg"
                            >
                                ECOMM - Store Margin (%)
                            </TableHeader>
                            <TableHeader
                                name="landed_cost"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Landed Cost
                            </TableHeader>
                            <TableHeader
                                name="landed_cost_sea"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Landed Cost Via SEA
                            </TableHeader>
                            <TableHeader
                                name="actual_landed_cost"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Actual Landed Cost
                            </TableHeader>
                            <TableHeader
                                name="working_landed_cost"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Working Store Cost
                            </TableHeader>
                            <TableHeader
                                name="working_store_cost_percentage"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Working Store Margin (%)
                            </TableHeader>
                            <TableHeader
                                name="ecom_working_store_cost"
                                queryParams={queryParams}
                                width="xl"
                            >
                                ECOMM - Working Store Cost
                            </TableHeader>
                            <TableHeader
                                name="ecom_working_store_cost_percentage"
                                queryParams={queryParams}
                                width="2xl"
                            >
                                ECOMM - Working Store Margin (%)
                            </TableHeader>
                            <TableHeader
                                name="working_landed_cost"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Working Landed Cost
                            </TableHeader>
                            <TableHeader
                                name="effective_date"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Effective Date
                            </TableHeader>
                            <TableHeader
                                name="duration_from"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Duration From
                            </TableHeader>
                            <TableHeader
                                name="duration_to"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Duration To
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                queryParams={queryParams}
                                width="lg"
                            >
                                Support Type
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                queryParams={queryParams}
                                width="lg"
                            >
                                Approved By
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                queryParams={queryParams}
                                width="lg"
                            >
                                Rejected By
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                queryParams={queryParams}
                                width="lg"
                            >
                                Updated By
                            </TableHeader>
                            <TableHeader
                                name="approved_at"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Approved At
                            </TableHeader>
                            <TableHeader
                                name="rejected_at"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Rejected At
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
                    <Tbody data={item_accounting_approvals?.data}>
                        {item_accounting_approvals &&
                            item_accounting_approvals?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center>
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
                                        {item.status === 'FOR APPROVAL' && 
                                            <RowAction
                                                type="link"
                                                action="edit"
                                                href={`item_master_accounting_approvals/approval_view/edit/${item.id}`}
                                            />
                                        }
                                        <RowAction
                                            type="link"
                                            action="view"
                                            href={`item_master_accounting_approvals/approval_view/view/${item.id}`}
                                        />
                                    </RowData>
                                    <RowStatus
                                        systemStatus={
                                            item.status === "FOR APPROVAL" ? "yellow" :
                                            item.status === "APPROVED - SCHEDULED" ? "cyan" :
                                            item.status === "APPROVED" ? 'active'
                                            : "inactive"
                                        }
                                    >
                                        {item.status}
                                    </RowStatus>
                                    <RowData >
                                        {item.get_item?.digits_code ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_brand?.brand_description ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_category?.category_description ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_margin_category?.margin_category_description ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.store_cost ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.store_cost_percentage ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.ecom_store_cost ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.ecom_store_cost_percentage ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.landed_cost ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.landed_cost_sea ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.actual_landed_cost ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.working_store_cost ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.working_store_cost_percentage ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.ecom_working_store_cost ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.ecom_working_store_cost_percentage ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.working_landed_cost ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.effective_date ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.duration_from  ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.duration_to ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_support_type?.support_type_description ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_approved_by?.name ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_rejected_by?.name ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_updated_by?.name ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.approved_at ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.rejected_at ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.updated_at ?? '-'}
                                    </RowData>

                                    
                                </Row>
                            ))}
                    </Tbody>
                </TableContainer>
                <Pagination extendClass={theme} paginate={item_accounting_approvals} />
            </ContentPanel>
        </>
    );
};

export default ItemMasterAccountingApprovals;
