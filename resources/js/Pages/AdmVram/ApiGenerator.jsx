import { Head, router, usePage } from '@inertiajs/react';
import React, { useContext, useEffect, useState } from 'react';
import ContentPanel from "../../Components/Table/ContentPanel";
import ApiDocumentation from './ApiDocumentation';
import ApiSecretKey from './ApiSecretKey';
import ApiCreation from './ApiCreation';

const ApiGenerator = ({page_title, api, secret_key, database_tables_and_columns}) => {
    const [activeTab, setActiveTab] = useState("tab1")

    const tabs = [
      { id: "tab1", icon: "fa fa-file-lines", label: "Api Documentation" },
      { id: "tab2", icon: "fa fa-key", label: "Api Secret Key" },
      { id: "tab3", icon: "fa fa-screwdriver-wrench", label: "Api Generator" },
    ]
    return (
        <>
        <Head title={page_title}/>
        <ContentPanel>
            <div className="w-full mx-auto">
                <div className="flex border-b">
                    {tabs.map((tab) => (
                        <button
                            key={tab.id}
                            onClick={() => setActiveTab(tab.id)}
                            className={`px-4 py-2 font-medium text-sm transition-colors focus:outline-none ${
                                activeTab === tab.id
                                ? "text-primary border-b-2 border-primary"
                                : "text-muted-foreground hover:text-foreground border-b-2 border-transparent"
                            }`}
                        >
                            <i className={`me-1 ${tab.icon}`}></i>
                            {tab.label}
                        </button>        
                    ))}
                </div>

                <div className="py-4">
                    {activeTab === "tab1" && (
                        <div className="space-y-2">
                            <ApiDocumentation api={api}></ApiDocumentation>
                        </div>
                    )}

                    {activeTab === "tab2" && (
                        <div className="space-y-2">
                            <ApiSecretKey secret_key={secret_key}></ApiSecretKey>
                        </div>
                    )}

                    {activeTab === "tab3" && (
                        <div className="space-y-2">
                            <ApiCreation table_x_columns={database_tables_and_columns}></ApiCreation>
                        </div>
                    )}
                </div>
            </div>
            </ContentPanel>
        </>
    );
};

export default ApiGenerator;
