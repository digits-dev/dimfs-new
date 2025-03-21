import React, { useEffect, useState } from 'react';
import { Head, Link, router, usePage, useForm } from "@inertiajs/react";
import InputComponent from '../../Components/Forms/Input';
import TableContainer from '../../Components/Table/TableContainer';
import Thead from '../../Components/Table/Thead';
import Row from '../../Components/Table/Row';
import TableHeader from '../../Components/Table/TableHeader';
import Tbody from '../../Components/Table/Tbody';
import RowData from '../../Components/Table/RowData';
import RowAction from '../../Components/Table/RowAction';
const ApiDocumentation = ({api}) => {
    const baseUrl = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : "" + "/api");
    
    return (
        <div className='space-y-3 p-3'>
            <InputComponent
                displayName="API BASE URL" 
                value={baseUrl}
                disabled
            />   

            <TableContainer data={api?.data}>
                <Thead>
                    <Row>
                        <TableHeader
                            name="id"
                            sortable={false}
                            width="sm"
                        >
                            No.
                        </TableHeader>
                        <TableHeader
                            name="api_name"
                            sortable={false}
                            width="lg"
                        >
                            API Name
                        </TableHeader>
                        <TableHeader
                            name="method"
                            sortable={false}
                            width="lg"
                        >
                            API Method
                        </TableHeader>
                        <TableHeader
                            name="endpoint"
                            sortable={false}
                            width="lg"
                        >
                            API End Point
                        </TableHeader>
                        <TableHeader
                            sortable={false}
                            width="md"
                            justify="center"
                        >
                            Action
                        </TableHeader>
                    </Row>
                </Thead>
                <Tbody data={api}>
                    {api &&
                        api.map((item, index) => (
                            <Row key={item.id + index}>
                                <RowData>
                                    {item.id}
                                </RowData>
                                <RowData>
                                    {item.name}
                                </RowData>
                                <RowData>
                                    {item.method}
                                </RowData>
                                <RowData>
                                    {item.endpoint}
                                </RowData>
                                <RowData center>
                                    <RowAction
                                        type="link"
                                        action="view"
                                        href={`/api_generator/view/${item.id}`}
                                    />
                                    <RowAction
                                        type="link"
                                        action="edit"
                                        href={`/api_generator/edit/${item.id}`}
                                    />
                                </RowData>
                                
                            </Row>
                    ))}
                </Tbody>
            </TableContainer>
            
        </div>
    );
};

export default ApiDocumentation;
