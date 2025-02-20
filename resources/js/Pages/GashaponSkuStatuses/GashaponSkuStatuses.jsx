import { Head, Link, router, usePage } from "@inertiajs/react";
import React, { useEffect, useState } from "react";
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import ContentPanel from "../../Components/Table/ContentPanel";
import TopPanel from "../../Components/Table/TopPanel";
import Tooltip from "../../Components/Tooltip/Tooltip";
import Button from "../../Components/Table/Buttons/Button";
import TableSearch from "../../Components/Table/TableSearch";
import TableContainer from "../../Components/Table/TableContainer";
import Thead from "../../Components/Table/Thead";
import Tbody from "../../Components/Table/Tbody";
import RowAction from '../../Components/Table/RowAction';
import Row from "../../Components/Table/Row";
import TableHeader from "../../Components/Table/TableHeader";
import RowData from "../../Components/Table/RowData";
import RowStatus from '../../Components/Table/RowStatus';
import Pagination from "../../Components/Table/Pagination";
import Modal from "../../Components/Modal/Modal";
import GashaponSkuStatusesAction from "./GashaponSkuStatusesAction";
import Export from "../../Components/Table/Buttons/Export";
import CustomFilter from "../../Components/Table/Buttons/CustomFilter";
import GashaponSkuStatusesFilter from "./GashaponSkuStatusesFilter";

const GashaponSkuStatuses = ({page_title, tableName, gashapon_sku_statuses, queryParams}) => {
    const {theme} = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);
    const [updateData, setUpdateData] = useState({
        id: "",
        status_description: "",
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
    }

    return (
        <>
            <Head title={page_title}/>
            <ContentPanel>
            <TopPanel>
                    <div className="inline-flex gap-1">
                        <Tooltip text='Refresh data' arrow='bottom'>
                            <Button
                                extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                                fontColor={textColorActive}
                                onClick={refreshTable}
                            >
                                <i className='fa fa-rotate-right text-base p-[1px]'></i>
                            </Button>
                        </Tooltip>
                        <Button
                            extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                            type="button"
                            fontColor={textColorActive}
                            onClick={()=>{handleModalClick(); setAction('Add');
                                setUpdateData({
                                    id: "",
                                    status_description: "",
                                    status: "",
                                })
                            
                            }}
                        > 
                          <i className="fa-solid fa-plus mr-1"></i>  Add Gashapon SKU Status
                        </Button>
                        <Export path="/gashapon_sku_statuses/export" page_title={page_title}/>
                    </div>
                    <div className='flex'>
                        <CustomFilter>
                            <GashaponSkuStatusesFilter/>
                        </CustomFilter>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={gashapon_sku_statuses?.data}>
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
                                name="status_description"
                                queryParams={queryParams}
                                width="xl"
                            >
                                Status Description
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
                    <Tbody data={gashapon_sku_statuses?.data}>
                        {gashapon_sku_statuses &&
                            gashapon_sku_statuses?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center>
                                        <RowAction
                                            type="button"
                                            action="edit"
                                            onClick={()=>{handleModalClick(); setAction('Update'); 
                                                setUpdateData({
                                                    id: item.id,
                                                    status_description: item.status_description,
                                                    status: item.status,
                                                })
                                            
                                            }}
                                        />
                                        <RowAction
                                            type="button"
                                            action="view"
                                            onClick={()=>{handleModalClick(); setAction('View'); 
                                                setUpdateData({
                                                    id: item.id,
                                                    status_description: item.status_description,
                                                    status: item.status,
                                                })
                                            
                                            }}
                                        />
                                    </RowData>
                                    <RowStatus
                                        isLoading={loading}
                                        center
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
                                        {item.status_description}
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
                <Pagination extendClass={theme} paginate={gashapon_sku_statuses} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={action == 'Add' ? "Add Gashapon SKU Status" : action == 'Update' ? 'Update Gashapon SKU Status' : 'Gashapon SKU Status Information'}
                width="xl"
                fontColor={textColorActive}
                btnIcon='fa fa-edit'
            >
                <GashaponSkuStatusesAction onClose={handleModalClick} action={action} updateData={updateData}/>
            </Modal>
            
        </>
    );
};

export default GashaponSkuStatuses;
