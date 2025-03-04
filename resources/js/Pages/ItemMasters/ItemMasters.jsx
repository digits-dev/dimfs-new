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
import Pagination from "../../Components/Table/Pagination";
import Export from "../../Components/Table/Buttons/Export";
import CustomFilter from "../../Components/Table/Buttons/CustomFilter";
import Filters from "../../Components/Filters/Filters";

const ItemMasters = ({
    page_title,
    tableName,
    item_masters,
    filter_inputs,
    queryParams,
    table_headers,
    can_create,
    can_update,
    can_export,
}) => {
    const { theme } = useTheme();
    const [loading, setLoading] = useState(false);
    const { primayActiveColor, textColorActive } = useThemeStyles(theme);
    const [pathname, setPathname] = useState(null);

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
                {table_headers.length != 0 ? (
                    <>
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
                                {can_create && (
                                    <Button
                                        extendClass={
                                            (["bg-skin-white"].includes(theme)
                                                ? primayActiveColor
                                                : theme) + " py-[5px] px-[10px]"
                                        }
                                        type="link"
                                        fontColor={textColorActive}
                                        href="item_masters/create_view"
                                    >
                                        <i className="fa-solid fa-plus mr-1"></i>{" "}
                                        Add Item Master
                                    </Button>
                                )}
                                {can_export && (
                                    <Export
                                        page_title="Item"
                                        path="/item_masters/export"
                                    />
                                )}
                            </div>
                            <div className="flex">
                                <CustomFilter>
                                    <Filters filter_inputs={filter_inputs} />
                                </CustomFilter>
                                <TableSearch queryParams={queryParams} />
                            </div>
                        </TopPanel>
                        <TableContainer data={item_masters?.data}>
                            <Thead>
                                <Row>
                                    <TableHeader
                                        sortable={false}
                                        width="md"
                                        justify="center"
                                    >
                                        Action
                                    </TableHeader>
                                    {table_headers &&
                                        table_headers?.map((header, index) => (
                                            <TableHeader
                                                key={index}
                                                name={header.name}
                                                queryParams={queryParams}
                                                width={header.width}
                                            >
                                                {header.header_name}
                                            </TableHeader>
                                        ))}
                                </Row>
                            </Thead>
                            <Tbody data={item_masters.data}>
                                {item_masters &&
                                    item_masters?.data?.map((item) => (
                                        <Row key={item.id}>
                                            <RowData center>
                                                {can_update && (
                                                    <RowAction
                                                        type="link"
                                                        action="edit"
                                                        hasTooltip
                                                        tooltipContent="Edit"
                                                        href={`item_masters/update_view/${item.id}`}
                                                    />
                                                )}
                                                <RowAction
                                                    type="link"
                                                    action="add_segmentation"
                                                    hasTooltip
                                                    tooltipContent="Add/Update Segmentation"
                                                    href={`item_masters/segmentaion/${item.id}`}
                                                />
                                                <RowAction
                                                    type="link"
                                                    action="view"
                                                    hasTooltip
                                                    tooltipContent="View"
                                                    href={`item_masters/view_details/${item.id}`}
                                                />
                                            </RowData>

                                            {table_headers?.map(
                                                (header, index) => {
                                                    const value =
                                                        header.table_join
                                                            ? header.table_join
                                                                  .split(".")
                                                                  .reduce(
                                                                      (
                                                                          acc,
                                                                          key
                                                                      ) =>
                                                                          acc?.[
                                                                              key
                                                                          ],
                                                                      item
                                                                  )
                                                            : item[header.name];

                                                    return (
                                                        <RowData
                                                            key={index}
                                                            isLoading={loading}
                                                        >
                                                            {value ?? "-"}
                                                        </RowData>
                                                    );
                                                }
                                            )}
                                        </Row>
                                    ))}
                            </Tbody>
                        </TableContainer>
                        <Pagination
                            extendClass={theme}
                            paginate={item_masters}
                        />
                    </>
                ) : (
                    <div className="flex flex-col items-center justify-center select-none">
                        <img
                            src="/images/others/403-logo.png"
                            className="w-[800px]"
                        />
                        <Link
                            href="/dashboard"
                            className="my-[20px] bg-blue-950 py-3 px-5 rounded-[50px] text-white font-poppins hover:opacity-70"
                        >
                            Go to Dashboard
                        </Link>
                    </div>
                )}
            </ContentPanel>
        </>
    );
};

export default ItemMasters;
