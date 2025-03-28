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
import ModuleHeadersAction from "./ModuleHeadersAction";
import Export from "../../Components/Table/Buttons/Export";
import CustomFilter from "../../Components/Table/Buttons/CustomFilter";

const ModuleHeaders = ({page_title, tableName, module_headers, queryParams, all_active_modules, all_modules, item_master_columns, gashapon_item_master_columns, database_tables_and_columns, rma_item_master_columns, item_master_accounting_columns}) => {
    const {theme} = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);
    const [updateData, setUpdateData] = useState({
        id: "",
        module_id: "",
        module_name: "",
        name: "",
        header_name: "",
        validation: "",
        width: "",
        type: "",
        table: "",
        table_join: "",
        table_select_value: "",
        table_select_label: "",
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
                                    module_id: "",
                                    module_name: "",
                                    name: "",
                                    header_name: "",
                                    validation: "",
                                    width: "",
                                    type: "",
                                    table: "",
                                    table_join: "",
                                    table_select_value: "",
                                    table_select_label: "",
                                    status: "",
                                })
                            
                            }}
                        > 
                          <i className="fa-solid fa-plus mr-1"></i>  Add Module Header
                        </Button>
                        <Button
                            type="link"
                            extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                            fontColor={textColorActive}
                            href="/module_headers/sort_view"
                        >
                            <i className="fa-solid fa-arrow-up-1-9"></i> Sort Headers
                        </Button>
                        <Export path="/module_headers/export" page_title={page_title}/>
                    </div>
                    <div className='flex'>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={module_headers?.data}>
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
                                name="header_name"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Header Name
                            </TableHeader>
                            <TableHeader
                                name="name"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Name
                            </TableHeader>
                            <TableHeader
                                name="validation"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Validation
                            </TableHeader>
                            <TableHeader
                                name="width"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Width
                            </TableHeader>
                            <TableHeader
                                name="type"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Type
                            </TableHeader>
                            <TableHeader
                                name="table"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Table
                            </TableHeader>
                            <TableHeader
                                name="table"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Join
                            </TableHeader>
                            <TableHeader
                                name="table_select_value"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Table Select Value
                            </TableHeader>
                            <TableHeader
                                name="table_select_label"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Table Select Label
                            </TableHeader>
                            <TableHeader
                                name="module_name"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Module Name
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
                    <Tbody data={module_headers?.data}>
                        {module_headers &&
                            module_headers?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center>
                                        <RowAction
                                            type="button"
                                            action="edit"
                                            onClick={()=>{handleModalClick(); setAction('Update'); 
                                                setUpdateData({
                                                    id: item.id,
                                                    module_id: item.module_id,
                                                    module_name: item.get_module.name,
                                                    name: item.name,
                                                    header_name: item.header_name,
                                                    validation: item.validation,
                                                    width: item.width,
                                                    type: item.type,
                                                    table: item.table,
                                                    table_join: item.table_join,
                                                    table_select_value: item.table_select_value,
                                                    table_select_label: item.table_select_label,
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
                                                    module_id: item.module_id,
                                                    module_name: item.get_module.name,
                                                    name: item.name,
                                                    header_name: item.header_name,
                                                    validation: item.validation,
                                                    width: item.width,
                                                    type: item.type,
                                                    table: item.table,
                                                    table_join: item.table_join,
                                                    table_select_value: item.table_select_value,
                                                    table_select_label: item.table_select_label,
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
                                        {item.header_name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.validation}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.width}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.type}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.table}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.table_join}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.table_select_value}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.table_select_label}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.get_module.name}
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
                <Pagination extendClass={theme} paginate={module_headers} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={action == 'Add' ? "Add Module Header" : action == 'Update' ? 'Update Module Header' : 'Module Header Information'}
                width="xl"
                fontColor={textColorActive}
                btnIcon='fa fa-edit'
            >
                <ModuleHeadersAction 
                    onClose={handleModalClick} 
                    action={action} 
                    updateData={updateData} 
                    all_active_modules={all_active_modules} 
                    all_modules={all_modules} 
                    gashapon_item_master_columns={gashapon_item_master_columns}
                    rma_item_master_columns={rma_item_master_columns} 
                    item_master_columns={item_master_columns}
                    item_master_accounting_columns={item_master_accounting_columns}
                    database_tables_and_columns={database_tables_and_columns}
                />
            </Modal>
            
        </>
    );
};

export default ModuleHeaders;
