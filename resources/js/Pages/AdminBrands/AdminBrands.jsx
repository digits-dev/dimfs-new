import { Head, Link, router, usePage } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import ContentPanel from "../../Components/Table/ContentPanel";
import TopPanel from "../../Components/Table/TopPanel";
import Tooltip from "../../Components/Tooltip/Tooltip";
import Button from "../../Components/Table/Buttons/Button";
import CustomFilter from "../../Components/Table/Buttons/CustomFilter";
import TableSearch from "../../Components/Table/TableSearch";
import { useTheme } from "../../Context/ThemeContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import Export from "../../Components/Table/Buttons/Export";
import TableContainer from "../../Components/Table/TableContainer";
import Thead from "../../Components/Table/Thead";
import Row from "../../Components/Table/Row";
import TableHeader from "../../Components/Table/TableHeader";
import Tbody from "../../Components/Table/Tbody";
import Pagination from "../../Components/Table/Pagination";
import RowData from "../../Components/Table/RowData";
import RowStatus from "../../Components/Table/RowStatus";
import RowAction from "../../Components/Table/RowAction";
import Modal from "../../Components/Modal/Modal";
import AdminBrandsAction from "./AdminBrandsAction";

const AdminBrands = ({page_title, admin_brands, queryParams, all_active_brand_types, all_brand_types}) => {
    const { auth } = usePage().props;
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);

    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);

    const [updateData, setUpdateData] = useState({
        id: "",
        brand_code: "",
        brand_description: "",
        brand_beacode: "",
        admin_brand_types_id: "",
        admin_brand_types_name: "",
        status: "",
    });

    console.log(admin_brands);

    const handleModalClick = () => {
        setIsModalOpen(!isModalOpen);
    }

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
                        
                        {auth.access.isCreate &&
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
                                        brand_code: "",
                                        brand_description: "",
                                        brand_beacode: "",
                                        admin_brand_types_id: "",
                                        status: "",
                                    });
                                }}
                            >
                                <i className="fa-solid fa-plus mr-1"></i> Add Brand
                            </Button>
                        }
                        <Export path="/admin_brands/export" page_title={page_title}/>
                    </div>
                    <div className="flex">
                        <CustomFilter>
                  
                        </CustomFilter>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={admin_brands?.data}>
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
                                name="brand_code"
                                queryParams={queryParams}
                                width="md"
                            >
                                Brand Code
                            </TableHeader>
                            <TableHeader
                                name="brand_description"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Brand Description
                            </TableHeader>
                            <TableHeader
                                name="brand_beacode"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Brand BEA Code
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="lg"
                            >
                                Brand Type Description
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
                    <Tbody data={admin_brands?.data}>
                        {admin_brands &&
                            admin_brands?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center>
                                        {auth.access.isUpdate &&
                                            <RowAction
                                                type="button"
                                                action="edit"
                                                onClick={() => {
                                                    handleModalClick();
                                                    setAction("Update");
                                                    setUpdateData({
                                                        id: item.id,
                                                        brand_code: item.brand_code,
                                                        brand_description: item.brand_description,
                                                        brand_beacode: item.brand_beacode,
                                                        admin_brand_types_id: item.admin_brand_types_id,
                                                        admin_brand_types_name: item.get_admin_brand_types?.brand_type_description,
                                                        status: item.status,
                                                    });
                                                }}
                                            />
                                        }
                                        
                                        <RowAction
                                            type="button"
                                            action="view"
                                            onClick={() => {
                                                handleModalClick();
                                                setAction("View");
                                                setUpdateData({
                                                    id: item.id,
                                                    brand_code: item.brand_code,
                                                    brand_description: item.brand_description,
                                                    brand_beacode: item.brand_beacode,
                                                    admin_brand_types_id: item.admin_brand_types_id,
                                                    admin_brand_types_name: item.get_admin_brand_types?.brand_type_description,
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
                                    <RowData >
                                        {item.brand_code ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.brand_description ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.brand_beacode ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_admin_brand_types?.brand_type_description ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_created_by?.name ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_updated_by?.name ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.created_at ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.updated_at ?? '-'}
                                    </RowData>
                                </Row>
                            ))}
                    </Tbody>
                </TableContainer>
                <Pagination extendClass={theme} paginate={admin_brands} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={
                    action == "Add"
                        ? "Add Brand"
                        : action == "Update"
                        ? "Update Brand"
                        : "Brand Information"
                }
                width="xl"
                fontColor={textColorActive}
                btnIcon="fa fa-edit"
            >
                <AdminBrandsAction 
                    onClose={handleModalClick}
                    action={action}
                    updateData={updateData}
                    all_brand_types={all_brand_types}
                    all_active_brand_types={all_active_brand_types} 
                />
            </Modal>
        </>
    );
};

export default AdminBrands;
