import React, { useContext, useState } from 'react'
import SidebarMenuCard from './SidebarMenuCard'
import SidebarMenuCardMultiple from './SidebarMenuCardMultiple'
import { NavbarContext } from "../../Context/NavbarContext";


const AdminSidebar = ({activeMenu, setActiveMenu, activeChildMenu, setActiveChildMenu}) => {

    const { setTitle } = useContext(NavbarContext);

    const SuperAdminMenus = [
        {
            name: "Privileges",
            type: "Route",
            slug: "privileges",
            icon: "fa fa-crown",
        },
        {
            name: "Users Management",
            type: "Route",
            slug: "users",
            icon: "fa fa-users",
        },
        {
            name: "Menu Management",
            type: "Route",
            slug: "menu_management",
            icon: "fa fa-bars",
        },
        {
            name: "Module Generator",
            type: "Route",
            slug: "module_generator",
            icon: "fa fa-th",
        },
        {
            name: "Admin Settings",
            type: "URL",
            slug: "adm_settings",
            icon: "fa fa-cogs",
            children: [
                {
                    name: "App Settings",
                    slug: "settings",
                    icon: "fa fa-cogs",
                },
                {
                    name: "Announcements",
                    slug: "announcements",
                    icon: "fa fa-info-circle",
                },
                {
                    name: "Notifications",
                    slug: "notifications",
                    icon: "fa fa-bell",
                }
            ]
        },
        {
            name: "Log User Access",
            type: "Route",
            slug: "logs",
            icon: "fa fa-history",
        },
        {
            name: "System Error Logs",
            type: "Route",
            slug: "system_error_logs",
            icon: "fa fa-history",
        },
    ];


    const handleMenuClick = (menuTitle) => {
        setActiveMenu((prev) => (prev === menuTitle ? null : menuTitle));
        setTitle(menuTitle);
    };

    const handleChildMenuClick = (childTitle, parentTitle) => {
        setActiveChildMenu(childTitle);
        setActiveMenu(parentTitle);
        setTitle(childTitle);
    };


  return (
    <div className='m-5'>
        <p className='text-xs font-bold text-gray-400 mb-5 text-nowrap'>ADMIN MENU</p>
        <div className='space-y-2'>
            {
                SuperAdminMenus && SuperAdminMenus.map((menu, index)=>{
                    if (menu.type === 'Route'){
                        return <SidebarMenuCard 
                                    href={menu.slug} 
                                    key={index} 
                                    menuTitle={menu.name} 
                                    icon={menu.icon}
                                    setActiveChildMenu={setActiveChildMenu}
                                    isMenuActive={activeMenu === menu.name} 
                                    onClick={() => handleMenuClick(menu.name)}
                                />
                    }
                    else
                    {
                        return <SidebarMenuCardMultiple 
                                    key={index}
                                    menuTitle={menu.name} 
                                    icon={menu.icon} 
                                    childMenus={menu.children} 
                                    isMenuActive={activeMenu === menu.name || (menu.children && menu.children.some(child => child.name === activeMenu))}
                                    isChildMenuActive={activeChildMenu}
                                    isMenuOpen={activeMenu === menu.name}
                                    onMenuClick={() => handleMenuClick(menu.name)}
                                    onChildMenuClick={handleChildMenuClick}
                                />

                    }
                }) 

            }
            
        </div>
    </div>
  )
}

export default AdminSidebar