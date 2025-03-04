import React, { useEffect, useState } from 'react';
import { Head, Link, router, usePage } from "@inertiajs/react";
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
    const [loading, setLoading] = useState(false);
    
    router.on('start', () => setLoading(true));
    router.on('finish', () => setLoading(false));

    return (
        <>
            <InputComponent
                displayName="API BASE URL" 
                value={baseUrl}
                disabled
            />   

            <TableContainer>
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
                            <Row key={item.id}>
                                <RowData isLoading={loading}>
                                    {item.id}
                                </RowData>
                                <RowData isLoading={loading}>
                                    {item.name}
                                </RowData>
                                <RowData isLoading={loading}>
                                    {item.method}
                                </RowData>
                                <RowData isLoading={loading}>
                                    {item.endpoint}
                                </RowData>
                                <RowData center>
                                    <RowAction
                                        type="button"
                                        action="view"
                                    />
                                    <RowAction
                                        type="button"
                                        action="edit"
                                    />
                                </RowData>
                                
                            </Row>
                    ))}
                </Tbody>
            </TableContainer>
            
        </>
    );
};

export default ApiDocumentation;
