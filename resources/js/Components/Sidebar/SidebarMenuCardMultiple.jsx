import { Link } from '@inertiajs/react';
import React, { useEffect, useState } from 'react'

const SidebarMenuCardMultiple = ({menuTitle = 'Sample Menu', icon = 'fa-solid fa-chart-simple', isMenuOpen , onMenuClick, onChildMenuClick, isMenuActive, isChildMenuActive, childMenus}) => {
  
    const getTextSizeClass = (text) => {
        if (text.length <= 10) return 'text-[13px]'; // Short
        if (text.length <= 20) return 'text-[13px]'; // Medium
        return 'text-[11px]'; // Long
      };

      const getChildTextSizeClass = (text) => {
        if (text.length <= 10) return 'text-[12px]'; // Short
        if (text.length <= 20) return 'text-[12px]'; // Medium
        return 'text-[10px]'; // Long
      };

      const colors = ['text-green-400', 'text-red-400', 'text-blue-400', 'text-yellow-400', 'text-purple-400']; 
      
  return (
    <div>
        {/* PARENT */}
        <div className={`cursor-pointer px-3 py-2.5 select-none flex text-gray-600 items-center border-2 border-white rounded-xl ${isMenuActive && 'bg-blue-500/40 !border-blue-500/60 text-white' } hover:bg-blue-500/40 hover:border-blue-500/60 hover:text-white`}
            onClick={onMenuClick}
        >
            <div className='w-5 h-5  flex items-center justify-center mr-2 flex-shrink-0'>
                <i className={icon}></i>
            </div>
            <p className={`${getTextSizeClass(menuTitle)} font-bold text-nowrap flex-1`}>{menuTitle}</p>
            <div className={`w-5 h-5  flex items-center justify-center transition-full duration-300 ${isMenuOpen ? '-rotate-180': ''}`}>
                <i className="fa-solid fa-caret-down text-xs"></i> 
            </div>
        </div>
        {/* CHILD */}
        <div className={`${isMenuOpen ? 'max-h-[100rem] opacity-100' : 'max-h-0 opacity-0'} flex flex-col text-gray-600 space-y-1 transition-all duration-500 ml-6 border-l-2 overflow-hidden `}>
            {childMenus && childMenus.map((child_menu, index)=>{

                const colorClass = colors[index % colors.length];
                return <Link href={child_menu.slug} onClick={() => onChildMenuClick(child_menu.name, menuTitle)}  key={child_menu.name + index} 
                            className={`p-1 flex items-center flex-1 ml-1 cursor-pointer border border-white rounded-lg first:mt-1 hover:bg-blue-500/40 hover:text-white hover:border-blue-500/60 ${isChildMenuActive == child_menu.name && 'bg-blue-500/40 !border-blue-500/60 text-white' }`}>
                                <div className='w-5 h-5 flex items-center justify-center mr-1 flex-shrink-0'>
                                    <i className={`fa-solid fa-circle text-[7px] ${colorClass}`}></i>
                                </div>
                                <span className={`${getChildTextSizeClass(child_menu.name)} font-semibold flex-1 text-nowrap`}>{child_menu.name}</span>
                        </Link>
            })}
        </div>
    </div>
    
  )
}

export default SidebarMenuCardMultiple