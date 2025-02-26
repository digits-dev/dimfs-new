import React, { useEffect, useState } from 'react';
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';
import UserSidebar from '../../Components/Sidebar/UserSidebar';
import AdminSidebar from '../../Components/Sidebar/AdminSidebar';

const AppSidebar = () => {
    const [isSidebarOpen, setIsSidebarOpen] = useState(true);
    const [activeMenu, setActiveMenu] = useState('Dashboard');
    const [activeChildMenu, setActiveChildMenu] = useState(null);

    const {theme} = useTheme();
    const { sideBarBgColor, bgColor, borderColor } = useThemeStyles(theme);

    const handleSidebarToggle = () => {
        setIsSidebarOpen(!isSidebarOpen);
    };
 
    return (
        <>
            <div
                className={`${theme} absolute z-100 cursor-pointer rounded-full -left-[-14px] md:-left-[-233px] top-[66px] md:top-[15px] lg:top-[15px] border-2 ${borderColor} p-2 flex items-center justify-center`}
                onClick={() => handleSidebarToggle()}
            >
                <img
                    src={`${theme == 'bg-skin-white' ? `/images/navigation/dashboard-arrow-icon-black.png` : `/images/navigation/dashboard-arrow-icon.png`}`}
                    className={`w-2 h-2 ${!isSidebarOpen && "rotate-180"} select-none`}
                />
            </div>
            <div className={`${isSidebarOpen ? 'w-[21rem]' : 'w-0'} transition-all duration-500 ${sideBarBgColor}`}>
                <div className=' max-h-[85vh] overflow-y-auto scrollbar-none'>
                    <UserSidebar activeMenu={activeMenu} setActiveMenu={setActiveMenu} activeChildMenu={activeChildMenu} setActiveChildMenu={setActiveChildMenu}/>
                    <AdminSidebar activeMenu={activeMenu} setActiveMenu={setActiveMenu} activeChildMenu={activeChildMenu} setActiveChildMenu={setActiveChildMenu}/>
                </div>
            </div>
            
        </>
    );
};

export default AppSidebar;
