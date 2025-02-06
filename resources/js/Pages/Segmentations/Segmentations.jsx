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
import SegmentationsAction from "./SegmentationsAction";

const Segmentations = ({ tableName, segmentations, queryParams }) => {
    const { theme } = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);
    const [updateData, setUpdateData] = useState({
        id: "",
        segmentation_column: "",
        segmentation_code: "",
        segmentation_description: "",
        import_header_name: "",
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
    };

    return (
        <>
            <Head title="Segmentations" />
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
                                    segmentation_column: "",
                                    segmentation_code: "",
                                    segmentation_description: "",
                                    import_header_name: "",
                                    status: "",
                                });
                            }}
                        >
                            <i className="fa-solid fa-plus mr-1"></i> Add
                            Segmentation
                        </Button>
                    </div>
                    <div className="flex">
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={segmentations?.data}>
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
                                name="segmentation_column"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Segmentation Column
                            </TableHeader>
                            <TableHeader
                                name="segmentation_code"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Segmentation Code
                            </TableHeader>
                            <TableHeader
                                name="segmentation_description"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Segmentation Description
                            </TableHeader>
                            <TableHeader
                                name="import_header_name"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Import Header Name
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
                    <Tbody data={segmentations?.data}>
                        {segmentations &&
                            segmentations?.data.map((item, index) => (
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
                                                    segmentation_column:
                                                        item.segmentation_column,
                                                    segmentation_code:
                                                        item.segmentation_code,
                                                    segmentation_description:
                                                        item.segmentation_description,
                                                    import_header_name:
                                                        item.import_header_name,
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
                                                    segmentation_column:
                                                        item.segmentation_column,
                                                    segmentation_code:
                                                        item.segmentation_code,
                                                    segmentation_description:
                                                        item.segmentation_description,
                                                    import_header_name:
                                                        item.import_header_name,
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
                                        {item.segmentation_column}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.segmentation_code}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.segmentation_description}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.import_header_name}
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
                <Pagination extendClass={theme} paginate={segmentations} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={
                    action == "Add"
                        ? "Add Segmentation"
                        : action == "Update"
                        ? "Update Segmentation"
                        : "Segmentation Information"
                }
                width="xl"
                fontColor={textColorActive}
                btnIcon="fa fa-edit"
            >
                <SegmentationsAction
                    onClose={handleModalClick}
                    action={action}
                    updateData={updateData}
                />
            </Modal>
        </>
    );
};

export default Segmentations;
