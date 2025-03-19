import { Head, Link, router, useForm, usePage } from "@inertiajs/react";
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
import Modal from "../../Components/Modal/Modal";
import Export from "../../Components/Table/Buttons/Export";
import CustomFilter from "../../Components/Table/Buttons/CustomFilter";
import EcommMarginMatricesAction from "./EcommMarginMatricesAction";
import EcommMarginMatricesFilter from "./EcommMarginMatricesFilter";

const EcommMarginMatrices = ({
    page_title,
    tableName,
    ecomm_margin_matrices,
    queryParams,
    all_active_brands,
    all_brands,
    all_active_vendor_types,
    all_vendor_types,
}) => {
    const { theme } = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);

    const [updateData, setUpdateData] = useState({
        id: "",
        brands_id: "",
        brand_description: "",
        margin_category: "",
        max: "",
        min: "",
        matrix_type: "",
        vendor_types_id: "",
        vendor_type_description: "",
        store_margin_percentage: "",
        status: "",
    });

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
            <Head title={page_title} />
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
                            onClick={() => {
                                handleModalClick();
                                setAction("Add");
                                setUpdateData({
                                    id: "",
                                    brands_id: "",
                                    brand_description: "",
                                    margin_category: "",
                                    max: "",
                                    min: "",
                                    matrix_type: "",
                                    vendor_types_id: "",
                                    vendor_type_description: "",
                                    store_margin_percentage: "",
                                    status: "",
                                });
                            }}
                        >
                            <i className="fa-solid fa-plus mr-1"></i> Add Margin Matrix
                        </Button>
                        <Export path="/ecomm_margin_matrices/export" page_title={page_title}/>
                    </div>
                    <div className="flex">
                        <CustomFilter>
                            <EcommMarginMatricesFilter all_brands={all_brands} all_vendor_types={all_vendor_types}/>
                        </CustomFilter>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={ecomm_margin_matrices?.data}>
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
                                sortable={false}
                                width="lg"
                            >
                                Brand
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="lg"
                            >
                                Margin Category
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="lg"
                            >
                                Max
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="lg"
                            >
                                Min
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="lg"
                            >
                                Store Margin (%)
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="lg"
                            >
                                Type
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="lg"
                            >
                                Vendor Type
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
                    <Tbody data={ecomm_margin_matrices?.data}>
                        {ecomm_margin_matrices &&
                            ecomm_margin_matrices?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center>
                                        <RowAction
                                            type="button"
                                            action="edit"
                                            onClick={() => {
                                                handleModalClick();
                                                setAction("Update");
                                                setUpdateData({
                                                    id: item.id,
                                                    brands_id: item.brands_id,
                                                    brand_description: item.get_brand?.brand_description,
                                                    margin_category: item.margin_category,
                                                    max: item.max,
                                                    min: item.min,
                                                    matrix_type: item.matrix_type,
                                                    vendor_types_id: item.vendor_types_id,
                                                    vendor_type_description: item.get_vendor_type?.vendor_type_description,
                                                    store_margin_percentage: item.store_margin_percentage,
                                                    status: item.status,
                                                });
                                            }}
                                        />
                                        <RowAction
                                            type="button"
                                            action="view"
                                            onClick={() => {
                                                handleModalClick();
                                                setAction("View");
                                                setUpdateData({
                                                    id: item.id,
                                                    brands_id: item.brands_id,
                                                    brand_description: item.get_brand?.brand_description,
                                                    margin_category: item.margin_category,
                                                    max: item.max,
                                                    min: item.min,
                                                    matrix_type: item.matrix_type,
                                                    vendor_types_id: item.vendor_types_id,
                                                    vendor_type_description: item.get_vendor_type?.vendor_type_description,
                                                    store_margin_percentage: item.store_margin_percentage,
                                                    status: item.status,
                                                });
                                            }}
                                        />
                                    </RowData>
                                    <RowStatus
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
                                    <RowData>
                                        {item.get_brand?.brand_description ?? '-'}
                                    </RowData>
                                    <RowData>
                                        {item.margin_category ?? '-'}
                                    </RowData>
                                    <RowData>
                                        {item.max ?? '-'}
                                    </RowData>
                                    <RowData>
                                        {item.min ?? '-'}
                                    </RowData>
                                    <RowData>
                                        {item.store_margin_percentage ?? '-'}
                                    </RowData>
                                    <RowData>
                                        {item.matrix_type ?? '-'}
                                    </RowData>
                                    <RowData>
                                        {item.get_vendor_type?.vendor_type_description ?? '-'}
                                    </RowData>
                                    <RowData>
                                        {item.get_created_by?.name}
                                    </RowData>
                                    <RowData>
                                        {item.get_updated_by?.name}
                                    </RowData>
                                    <RowData>
                                        {item.created_at}
                                    </RowData>
                                    <RowData>
                                        {item.updated_at}
                                    </RowData>
                                </Row>
                            ))}
                    </Tbody>
                </TableContainer>
                <Pagination extendClass={theme} paginate={ecomm_margin_matrices} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={
                    action == "Add"
                        ? "Add Margin Matrix"
                        : action == "Update"
                        ? "Update Margin Matrix"
                        : "Margin Matrix Information"
                }
                width="xl"
                fontColor={textColorActive}
                btnIcon="fa fa-edit"
            >
                <EcommMarginMatricesAction
                    onClose={handleModalClick}
                    action={action}
                    updateData={updateData}
                    all_brands={all_brands}
                    all_active_brands={all_active_brands}
                    all_vendor_types={all_vendor_types}
                    all_active_vendor_types={all_active_vendor_types}
                />
            </Modal>
        </>
    );
};

export default EcommMarginMatrices;
