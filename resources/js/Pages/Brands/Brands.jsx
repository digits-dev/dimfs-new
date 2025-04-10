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
import BrandAction from "./BrandAction";
import Export from "../../Components/Table/Buttons/Export";
import CustomFilter from "../../Components/Table/Buttons/CustomFilter";
import BrandsFilter from "./BrandsFilter";

const Brands = ({
    page_title,
    tableName,
    brands,
    queryParams,
    all_active_brand_groups,
    all_brand_groups,
}) => {
    const { theme } = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);

    const [updateData, setUpdateData] = useState({
        id: "",
        brand_code: "",
        brand_description: "",
        brand_groups_id: "",
        brand_groups_name: "",
        contact_name: "",
        contact_email: "",
        status: "",
    });

    router.on("start", () => setLoading(true));
    router.on("finish", () => setLoading(false));

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
                                    brand_code: "",
                                    brand_description: "",
                                    brand_groups_id: "",
                                    brand_groups_name: "",
                                    contact_name: "",
                                    contact_email: "",
                                    status: "",
                                });
                            }}
                        >
                            <i className="fa-solid fa-plus mr-1"></i> Add Brand
                        </Button>
                        <Export path="/brands/export" page_title={page_title}/>
                    </div>
                    <div className="flex">
                        <CustomFilter>
                          <BrandsFilter all_brand_groups={all_brand_groups}/>
                        </CustomFilter>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={brands?.data}>
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
                                name="brand_description"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Brand Description
                            </TableHeader>
                            <TableHeader
                                name="brand_code"
                                queryParams={queryParams}
                                width="md"
                            >
                                Brand Code
                            </TableHeader>
                            <TableHeader
                                name="brand_group"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Brand Group
                            </TableHeader>
                            <TableHeader
                                name="contact_name"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Contact Name
                            </TableHeader>
                            <TableHeader
                                name="contact_email"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Contact Email
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
                    <Tbody data={brands?.data}>
                        {brands &&
                            brands?.data.map((item, index) => (
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
                                                    brand_code: item.brand_code,
                                                    brand_description:
                                                        item.brand_description,
                                                    brand_groups_id:
                                                        item.brand_groups_id,
                                                    brand_groups_name:
                                                        item.get_brand_group
                                                            ?.brand_group_description,
                                                    contact_name:
                                                        item.contact_name,
                                                    contact_email:
                                                        item.contact_email,
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
                                                    brand_code: item.brand_code,
                                                    brand_description:
                                                        item.brand_description,
                                                    brand_groups_id:
                                                        item.brand_groups_id,
                                                    brand_groups_name:
                                                        item.get_brand_group
                                                            ?.brand_group_description,
                                                    contact_name:
                                                        item.contact_name,
                                                    contact_email:
                                                        item.contact_email,
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
                                        {item.brand_description}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.brand_code}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {
                                            item.get_brand_group
                                                ?.brand_group_description
                                        }
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.contact_name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.contact_email}
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
                <Pagination extendClass={theme} paginate={brands} />
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
                <BrandAction
                    onClose={handleModalClick}
                    action={action}
                    updateData={updateData}
                    all_brand_groups={all_brand_groups}
                    all_active_brand_groups={all_active_brand_groups}
                />
            </Modal>
        </>
    );
};

export default Brands;
