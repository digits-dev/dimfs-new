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

const AdminMarginCategoies = ({page_title, admin_margin_categories, queryParams}) => {
    const { auth } = usePage().props;
    const { theme } = useTheme();
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);

    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);

    const [updateData, setUpdateData] = useState({
        id: "",
        admin_sub_classifications_id: "",
        margin_category_code: "",
        margin_category_description: "",
        status: "",
    });

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
                                        admin_sub_classifications_id: "",
                                        margin_category_code: "",
                                        margin_category_description: "",
                                        status: "",
                                    });
                                }}
                            >
                                <i className="fa-solid fa-plus mr-1"></i> Add Margin Category
                            </Button>
                        }
                        <Export path="/admin_margin_categories/export" page_title={page_title}/>
                    </div>
                    <div className="flex">
                        <CustomFilter>
                  
                        </CustomFilter>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={admin_margin_categories?.data}>
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
                                name="margin_category_code"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Margin Category Code
                            </TableHeader>
                            <TableHeader
                                name="margin_category_description"
                                queryParams={queryParams}
                                width="xl"
                            >
                                Margin Category Description
                            </TableHeader>
                            <TableHeader
                                sortable={false}
                                width="xl"
                            >
                                Sub Classification Description
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
                    <Tbody data={admin_margin_categories?.data}>
                        {admin_margin_categories &&
                            admin_margin_categories?.data.map((item, index) => (
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
                                                        admin_sub_classifications_id: item.admin_sub_classifications_id,
                                                        margin_category_code: item.margin_category_code,
                                                        margin_category_description: item.margin_category_description,   
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
                                                    admin_sub_classifications_id: item.admin_sub_classifications_id,
                                                    margin_category_code: item.margin_category_code,
                                                    margin_category_description: item.margin_category_description,
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
                                        {item.margin_category_code ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.margin_category_description ?? '-'}
                                    </RowData>
                                    <RowData >
                                        {item.get_admin_sub_classification?.sub_class_description ?? '-'}
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
                <Pagination extendClass={theme} paginate={admin_margin_categories} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={
                    action == "Add"
                        ? "Add Margin Category"
                        : action == "Update"
                        ? "Update Margin Category"
                        : "Margin Category Information"
                }
                width="xl"
                fontColor={textColorActive}
                btnIcon="fa fa-edit"
            >
              
            </Modal>
        </>
    );
};

export default AdminMarginCategoies;
