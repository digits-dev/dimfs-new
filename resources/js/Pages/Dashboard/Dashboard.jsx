import React, { useEffect } from "react";
import { Head, usePage } from "@inertiajs/react";
import Overview from "../../Components/Dashboard/Overview";

const Dashboard = ({ customer, orders, devices, orders_count_wdate }) => {
    const { auth } = usePage().props;
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
        <>
            <Head title="Dashboard" />
            {auth.access.isView && auth.access.isRead && 
              <Overview
                customer={customer}
                orders={orders}
                devices={devices}
            />
            }
          
        </>
    );
};

export default Dashboard;
