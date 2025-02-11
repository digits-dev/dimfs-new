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
import RmaMarginCategoriesAction from "./RmaMarginCategoriesAction";
import { useToast } from '../../Context/ToastContext';

const RmaMarginCategories = ({page_title, tableName, rma_margin_categories, queryParams, all_active_rma_sub_classifications, all_rma_sub_classifications}) => {
    const {theme} = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [action, setAction] = useState(null);
    const [updateData, setUpdateData] = useState({
        id: "",
        rma_sub_classifications_id: "",
        rma_sub_classification_name: "",
        margin_category_code: "",
        margin_category_description: "",
        status: "",
    });

    const { handleToast } = useToast();

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

    
    const handleExport = (e) => {
        e.preventDefault();
        
        Swal.fire({
            title: `<p class="font-poppins text-3xl">Do you want to Export ${page_title}?</p>`,
            showCancelButton: true,
            confirmButtonText: `Export`,
            confirmButtonColor: buttonSwalColor,
            icon: 'question',
            iconColor: buttonSwalColor,
            reverseButtons: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    window.location.href = '/rma_margin_categories/export' + window.location.search;
                } catch (error) {
                    {
                        handleToast &&
                            handleToast(
                                "Something went wrong, please try again later.",
                                "Error"
                            );
                    }
                }
            }
        });
    };

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
                                    rma_sub_classifications_id: "",
                                    rma_sub_classification_name: "",
                                    margin_category_code: "",
                                    margin_category_description: "",
                                    status: "",
                                })
                            
                            }}
                        > 
                          <i className="fa-solid fa-plus mr-1"></i>  Add RMA Margin Category
                        </Button>
                        <Button
                            extendClass={(['bg-skin-white'].includes(theme) ? primayActiveColor : theme)+" py-[5px] px-[10px]"}
                            type="button"
                            fontColor={textColorActive}
                            onClick={handleExport}
                        > 
                          <i className="fa-solid fa-download mr-1"></i> Export
                        </Button>
                    </div>
                    <div className='flex'>
                        <TableSearch queryParams={queryParams} />
                    </div>
                </TopPanel>
                <TableContainer data={rma_margin_categories?.data}>
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
                                width="2xl"
                            >
                                RMA Margin Category Code
                            </TableHeader>
                            <TableHeader
                                name="margin_category_description"
                                queryParams={queryParams}
                                width="2xl"
                            >
                                RMA Margin Category Description
                            </TableHeader>
                            <TableHeader
                                name="rma_sub_classifications_id"
                                queryParams={queryParams}
                                width="2xl"
                            >
                                RMA Sub Classification Description
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
                    <Tbody data={rma_margin_categories?.data}>
                        {rma_margin_categories &&
                            rma_margin_categories?.data.map((item, index) => (
                                <Row key={item.id}>
                                    <RowData center>
                                        <RowAction
                                            type="button"
                                            action="edit"
                                            onClick={()=>{handleModalClick(); setAction('Update'); 
                                                setUpdateData({
                                                    id: item.id,
                                                    rma_sub_classifications_id: item.rma_sub_classifications_id,
                                                    rma_sub_classification_name: item.get_rma_sub_classification?.sub_classification_description,
                                                    margin_category_code: item.margin_category_code,
                                                    margin_category_description: item.margin_category_description,
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
                                                    rma_sub_classifications_id: item.rma_sub_classifications_id,
                                                    rma_sub_classification_name: item.get_rma_sub_classification?.sub_classification_description,
                                                    margin_category_code: item.margin_category_code,
                                                    margin_category_description: item.margin_category_description,
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
                                        {item.margin_category_code}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.margin_category_description}
                                    </RowData>
                                    <RowData isLoading={loading}>
                                        {item.get_rma_sub_classification?.sub_classification_description}
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
                <Pagination extendClass={theme} paginate={rma_margin_categories} />
            </ContentPanel>
            <Modal
                theme={theme}
                show={isModalOpen}
                onClose={handleModalClick}
                title={action == 'Add' ? "Add RMA Margin Category" : action == 'Update' ? 'Update RMA Margin Category' : 'RMA Margin Category Information'}
                width="xl"
                fontColor={textColorActive}
                btnIcon='fa fa-edit'
            >
                <RmaMarginCategoriesAction onClose={handleModalClick} action={action} updateData={updateData} all_active_rma_sub_classifications={all_active_rma_sub_classifications} all_rma_sub_classifications={all_rma_sub_classifications}/>
            </Modal>
            
        </>
    );
};

export default RmaMarginCategories;
