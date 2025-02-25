import React, { useEffect } from "react";
import { Head, usePage } from "@inertiajs/react";
import ContentPanel from "../../Components/Table/ContentPanel";
import useThemeStyles from "../../Hooks/useThemeStyles";
import { useTheme } from "../../Context/ThemeContext";
import ItemCard from "../../Components/Dashboard/ItemCard";

const Dashboard = ({ item_master_stats, item_master_creation_counter, item_master_update_counter }) => {
    const { auth } = usePage().props;
    const { theme } = useTheme();
    const { textColor } = useThemeStyles(theme);
    useEffect(() => {
        if (auth.user) {
            window.history.pushState(
                null,
                document.title,
                window.location.href
            );

            window.addEventListener("popstate", (event) => {
                window.history.pushState(
                    null,
                    document.title,
                    window.location.href
                );
            });
        }

    }, [auth.user]);
   
    return (
        <div className={`${textColor}`}>
            <Head title="Dashboard" />
            {auth.access.isView && auth.access.isRead && 
              <ContentPanel>
                <p className="font-semibold mb-2">Overview</p>
                <div className="font-poppins flex flex-col gap-3">
                    <ItemCard title="Item Master" data={item_master_stats} create_data={item_master_creation_counter} update_data={item_master_update_counter}/>
                </div>
              </ContentPanel>
            }
          
        </div>
    );
};

export default Dashboard;
