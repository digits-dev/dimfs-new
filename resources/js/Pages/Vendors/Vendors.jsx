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
import Modal from "../../Components/Modal/Modal";
import VendorsAction from "./VendorsAction";
import Export from "../../Components/Table/Buttons/Export";
import CustomFilter from "../../Components/Table/Buttons/CustomFilter";
import VendorsFilter from "./VendorsFilter";

const Vendors = ({ 
    page_title, 
    tableName, 
    vendors, 
    queryParams,
    all_active_brands,
    all_brands,
    all_active_vendor_types,
    all_vendor_types,
    all_active_incoterms,
    all_incoterms,
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
        brands_name: "",
        vendor_types_id: "",
        vendor_types_name: "",
        incoterms_id: "",
        incoterms_name: "",
        vendor_name: "",
        status: "",
    });

    router.on('start', () => setLoading(true));
    router.on('finish', () => setLoading(false));

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
    };

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
                                    brands_name: "",
                                    vendor_types_id: "",
                                    vendor_types_name: "",
                                    incoterms_id: "",
                                    incoterms_name: "",
                                    vendor_name: "",
                                    status: "",
                                });
                            }}
                        >
                            <i className="fa-solid fa-plus mr-1"></i> Add Vendor
                        </Button>
                        <Export path="/vendors/export" page_title={page_title}/>
                    </div>
                    <div className="flex">
                        <CustomFilter>
                            <VendorsFilter all_brands={all_brands} all_vendor_types={all_vendor_types} all_incoterms={all_incoterms}/>
                        </CustomFilter>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={vendors?.data}>
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
                                name="vendor_name"
                                queryParams={queryParams}
                                width="xl"
                            >
                                Vendor Name
                            </TableHeader>
                            <TableHeader
                                name="brands_id"
                                queryParams={queryParams}
                                width="xl"
                            >
                                Brand Description
                            </TableHeader>
                            <TableHeader
                                name="vendor_types_id"
                                queryParams={queryParams}
                                width="xl"
                            >
                                Vendor Type Description
                            </TableHeader>
                            <TableHeader
                                name="incoterms_id"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Incoterm Description
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
                    <Tbody data={vendors?.data}>
                        {vendors &&
                            vendors?.data.map((item, index) => (
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
                                                    brands_name: item.get_brand?.brand_description,
                                                    vendor_types_id: item.vendor_types_id,
                                                    vendor_types_name: item.get_vendor_type.vendor_type_description,
                                                    incoterms_id: item.incoterms_id,
                                                    incoterms_name: item.get_incoterm?.incoterms_description,
                                                    vendor_name: item.vendor_name,
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
                                                    brands_name: item.get_brand?.brand_description,
                                                    vendor_types_id: item.vendor_types_id,
                                                    vendor_types_name: item.get_vendor_type.vendor_type_description,
                                                    incoterms_id: item.incoterms_id,
                                                    incoterms_name: item.get_incoterm?.incoterms_description,
                                                    vendor_name: item.vendor_name,
                                                    status: item.status,
                                                });
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
                                        {item.vendor_name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.get_brand?.brand_description}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.get_vendor_type.vendor_type_description}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.get_incoterm?.incoterms_description}
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
                <Pagination extendClass={theme} paginate={vendors} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={
                    action == "Add"
                        ? "Add Vendor"
                        : action == "Update"
                        ? "Update Vendor"
                        : "Vendor Information"
                }
                width="xl"
                fontColor={textColorActive}
                btnIcon="fa fa-edit"
            >
                <VendorsAction
                    onClose={handleModalClick}
                    action={action}
                    updateData={updateData}
                    all_active_brands={all_active_brands}
                    all_brands={all_brands}
                    all_active_vendor_types={all_active_vendor_types}
                    all_vendor_types={all_vendor_types}
                    all_active_incoterms={all_active_incoterms}
                    all_incoterms={all_incoterms}
                />
            </Modal>
        </>
    );
};

export default Vendors;
