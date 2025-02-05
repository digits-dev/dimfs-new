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
import CountersAction from "./CountersAction";


const Counters = ({tableName, counters, queryParams, all_active_modules, all_modules}) => {
    const {theme} = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);
    const [updateData, setUpdateData] = useState({
        id: "",
        cms_modules_id: "",
        module_name: "",
        code_1: "",
        code_2: "",
        code_3: "",
        code_4: "",
        code_5: "",
        code_6: "",
        code_7: "",
        code_8: "",
        code_9: "",
        status: "",
    })

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
            <Head title="Counters"/>
            <ContentPanel>
            <TopPanel>
                    <div className="inline-flex gap-1">
                        <Tooltip text='Refresh data' arrow='bottom'>
                            <Button
                                extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                                fontColor={textColorActive}
                                onClick={refreshTable}
                            >
                                <i className='fa fa-table text-base p-[1px]'></i>
                            </Button>
                        </Tooltip>
                        <Button
                            extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                            type="button"
                            fontColor={textColorActive}
                            onClick={()=>{handleModalClick(); setAction('Add');
                                setUpdateData({
                                    id: "",
                                    cms_modules_id: "",
                                    module_name: "",
                                    code_1: "",
                                    code_2: "",
                                    code_3: "",
                                    code_4: "",
                                    code_5: "",
                                    code_6: "",
                                    code_7: "",
                                    code_8: "",
                                    code_9: "",
                                    status: "",
                                })
                            
                            }}
                        > 
                          <i className="fa-solid fa-plus mr-1"></i>  Add Counter
                        </Button>
                    </div>
                    <div className='flex'>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={counters?.data}>
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
                                name="module_name"
                                queryParams={queryParams}
                                width="lg"
                            >
                                Module Name
                            </TableHeader>
                            <TableHeader
                                name="code_1"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 1
                            </TableHeader>
                            <TableHeader
                                name="code_2"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 2
                            </TableHeader>
                            <TableHeader
                                name="code_3"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 3
                            </TableHeader>
                            <TableHeader
                                name="code_4"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 4
                            </TableHeader>
                            <TableHeader
                                name="code_5"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 5
                            </TableHeader>
                            <TableHeader
                                name="code_6"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 6
                            </TableHeader>
                            <TableHeader
                                name="code_7"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 7
                            </TableHeader>
                            <TableHeader
                                name="code_8"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 8
                            </TableHeader>
                            <TableHeader
                                name="code_9"
                                queryParams={queryParams}
                                width="md"
                            >
                                Code 9
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
                    <Tbody data={counters?.data}>
                        {counters &&
                            counters?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center>
                                        <RowAction
                                            type="button"
                                            action="edit"
                                            onClick={()=>{handleModalClick(); setAction('Update'); 
                                                setUpdateData({
                                                    id: item.id,
                                                    cms_modules_id: item.cms_moduls_id,
                                                    module_name: item.module_name,
                                                    code_1: item.code_1,
                                                    code_2: item.code_2,
                                                    code_3: item.code_3,
                                                    code_4: item.code_4,
                                                    code_5: item.code_5,
                                                    code_6: item.code_6,
                                                    code_7: item.code_7,
                                                    code_8: item.code_8,
                                                    code_9: item.code_9,
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
                                                    cms_modules_id: item.cms_moduls_id,
                                                    module_name: item.module_name,
                                                    code_1: item.code_1,
                                                    code_2: item.code_2,
                                                    code_3: item.code_3,
                                                    code_4: item.code_4,
                                                    code_5: item.code_5,
                                                    code_6: item.code_6,
                                                    code_7: item.code_7,
                                                    code_8: item.code_8,
                                                    code_9: item.code_9,
                                                    status: item.status,
                                                })
                                            
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
                                        {item.module_name}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_1}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_2}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_3}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_4}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_5}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_6}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_7}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_8}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.code_9}
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
                <Pagination extendClass={theme} paginate={counters} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={action == 'Add' ? "Add Counter" : action == 'Update' ? 'Update Counter' : 'Counter Information'}
                width="xl"
                fontColor={textColorActive}
                btnIcon='fa fa-edit'
            >
                <CountersAction onClose={handleModalClick} action={action} updateData={updateData} all_active_modules={all_active_modules} all_modules={all_modules}/>
            </Modal>
            
        </>
    );
};

export default Counters;
