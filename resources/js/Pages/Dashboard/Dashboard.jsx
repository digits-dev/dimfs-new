import React, { useEffect, useState } from "react";
import { Head, usePage } from "@inertiajs/react";
import ContentPanel from "../../Components/Table/ContentPanel";
import useThemeStyles from "../../Hooks/useThemeStyles";
import { useTheme } from "../../Context/ThemeContext";
import ItemCard from "../../Components/Dashboard/ItemCard";
import ChangePassModal from "../../Components/Modal/ChangePassModal";

const Dashboard = ({ 
    item_master_stats, 
    item_master_creation_counter, 
    item_master_update_counter, 
    gashapon_item_master_stats, 
    gashapon_item_master_creation_counter, 
    gashapon_item_master_update_counter,
    rma_item_master_stats,
    rma_item_master_creation_counter,
    rma_item_master_update_counter,
    dashboard_settings_data }) => {
    const { auth } = usePage().props;
    const { theme } = useTheme();
    const { textColor, sideBarBgColor } = useThemeStyles(theme);

    const [activeTab, setActiveTab] = useState("tab1")

    const sample = [
        'https://app.powerbi.com/view?r=eyJrIjoiOGJmZjg0NDktZTc0YS00OTFhLWEwNjctMmVjODJhZWFkZGI2IiwidCI6ImVhNjUwNjA1LTVlOGQtNGRkNC1iNzhmLTAyZTNlZDVmZWQ5OCIsImMiOjEwfQ%3D%3D',
        'https://app.powerbi.com/view?r=eyJrIjoiMzk2NDc1MjktNTAxYy00YzQyLTlkMmItMDRjYWY5YmM0OGMyIiwidCI6ImVhNjUwNjA1LTVlOGQtNGRkNC1iNzhmLTAyZTNlZDVmZWQ5OCIsImMiOjEwfQ%3D%3D'
    ]
    
    const tabs = [
      { id: "tab1", image: "/images/dashboard/item-master.png", label: "Item Master" },
      { id: "tab2", image: "/images/dashboard/gashapon.png", label: "Gashapon Item Master" },
      { id: "tab3", image: "/images/dashboard/rma.png", label: "RMA Item Master" },
    ]

    return (
        <div className={`${textColor}`}>
            <Head title="Dashboard" />
            <ChangePassModal/>
            
            {auth.access.isView && auth.access.isRead && 
                <>
                    {dashboard_settings_data.has_default_dashboard == 'Yes' && 
                        <div className={`w-full mx-auto ${sideBarBgColor} mt-5 shadow-menus rounded-lg overflow-hidden`}>
                            <div className={`flex  ${theme == 'bg-skin-blue' ? 'bg-gray-200': 'bg-black/20'}`}>
                                {tabs.map((tab) => (
                                    <button
                                        key={tab.id}
                                        onClick={() => setActiveTab(tab.id)}
                                        className={`flex items-center justify-center px-5 py-3 font-medium text-sm transition-colors focus:outline-none ${
                                            activeTab === tab.id
                                            ? `${sideBarBgColor} rounded-t-lg`
                                            : "text-muted-foreground text-gray-600 hover:text-foreground hover:bg-gray-300 "
                                        }`}
                                    >
                                            <img
                                                src={tab.image}
                                                className={`w-4 h-4 md:w-5 md:h-5 md:mr-2 cursor-pointer duration-500 
                                                ${
                                                    activeTab === tab.id
                                                    ? "opacity-100"
                                                    : "opacity-50"
                                                }`}
                                            />
                                        
                                        <div className="hidden md:block">{tab.label}</div>
                                    </button>        
                                ))}
                            </div>
                            <div className="px-3 py-2">
                                {activeTab === "tab1" && (
                                    <div className="p-2 select-none">
                                        <p className="font-semibold mb-2">Overview</p>
                                        <div className="font-poppins flex flex-col gap-3">
                                            <ItemCard title="Item Master" data={item_master_stats} create_data={item_master_creation_counter} update_data={item_master_update_counter} create_table_title="Item Creation Counter" update_table_title="Update Item Counter"/>
                                        </div>
                                    </div>
                                )}

                                {activeTab === "tab2" && (
                                    <div className="p-2 select-none">
                                        <p className="font-semibold mb-2">Overview</p>
                                        <div className="font-poppins flex flex-col gap-3">
                                            <ItemCard title="Gashapon Item Master" data={gashapon_item_master_stats} create_data={gashapon_item_master_creation_counter} update_data={gashapon_item_master_update_counter} create_table_title="Gashapon Item Creation Counter" update_table_title="Update Gashapon Item Counter"/>
                                        </div>
                                    </div>
                                )}

                                {activeTab === "tab3" && (
                                    <div className="p-2 select-none">
                                        <p className="font-semibold mb-2">Overview</p>
                                        <div className="font-poppins flex flex-col gap-3">
                                            <ItemCard title="RMA Item Master" data={rma_item_master_stats} create_data={rma_item_master_creation_counter} update_data={rma_item_master_update_counter} create_table_title="RMA Item Creation Counter" update_table_title="Update RMA Item Counter"/>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    }
                    {dashboard_settings_data.has_embedded_dashboard == 'Yes' && 
                        sample.map((item)=>(
                            <div style={{ position: 'relative', width: '100%', paddingBottom: '56.25%', height: 0, overflow: 'hidden' }}>
                                <iframe
                                    title="IT Ops Dashboard"
                                    style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%' }}
                                    src={item}
                                    frameBorder="0"
                                    allowFullScreen={true}
                                ></iframe>
                            </div>
                        ))
                    }
                </>
              
            }
          
        </div>
    );
};

export default Dashboard;
