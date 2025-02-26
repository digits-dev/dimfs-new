import { Link } from '@inertiajs/react';
import React from 'react'
import useThemeStyles from '../../Hooks/useThemeStyles';
import { useTheme } from '../../Context/ThemeContext';

const SidebarMenuCard = ({menuTitle = 'Sample Menu', icon = 'fa-solid fa-chart-simple', href, isMenuActive, onClick, setActiveChildMenu}) => {
  const {theme} = useTheme();
  const { 
            sidebarHoverTextColor,
            sidebarHoverMenuBgColor, 
            sidebarHoverMenuBorderColor,
            sidebarActiveTextColor,
            sideBarTextColor,
            sidebarActiveMenuBgColor,
            sidebarActiveMenuBorderColor,
            sidebarBorderColor,
        } = useThemeStyles(theme);

  const getTextSizeClass = (text) => {
    if (text.length <= 10) return 'text-[13px]';
    if (text.length <= 20) return 'text-[13px]';
    return 'text-[11px]';
  };


  return (
    <Link 
      onClick={()=>{onClick(); setActiveChildMenu(null)}} 
      href={'/' + href} 
      className={`cursor-pointer select-none px-3 py-2.5 overflow-hidden flex ${sideBarTextColor} items-center border-2 ${sidebarBorderColor} rounded-xl ${isMenuActive && sidebarActiveMenuBorderColor + ' ' + sidebarActiveMenuBgColor + ' ' + sidebarActiveTextColor } ${sidebarHoverMenuBgColor} ${sidebarHoverMenuBorderColor} ${sidebarHoverTextColor}`}>
        <div className='w-5 h-5  flex items-center justify-center mr-2 flex-shrink-0'>
            <i className={icon}></i>
        </div>
        <p className={`font-bold flex-shrink-0 ${getTextSizeClass(menuTitle)}`}>{menuTitle}</p>
    </Link>
  )
}

export default SidebarMenuCard