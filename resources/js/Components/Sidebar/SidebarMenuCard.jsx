import { Link } from '@inertiajs/react';
import React from 'react'

const SidebarMenuCard = ({menuTitle = 'Sample Menu', icon = 'fa-solid fa-chart-simple', href, isMenuActive, onClick, setActiveChildMenu}) => {

  const getTextSizeClass = (text) => {
    if (text.length <= 10) return 'text-[13px]'; // Short
    if (text.length <= 20) return 'text-[13px]'; // Medium
    return 'text-[11px]'; // Long
  };


  return (
    <Link 
      onClick={()=>{onClick(); setActiveChildMenu(null)}} 
      href={href} 
      className={`cursor-pointer select-none px-3 py-2.5 overflow-hidden flex select-none text-gray-600 items-center border-2 border-white rounded-xl ${isMenuActive && 'bg-blue-500/40 !border-blue-500/60 text-white' } hover:bg-blue-500/40 hover:border-blue-500/60 hover:text-white`}>
        <div className='w-5 h-5  flex items-center justify-center mr-2 flex-shrink-0'>
            <i className={icon}></i>
        </div>
        <p className={`font-bold flex-shrink-0 ${getTextSizeClass(menuTitle)}`}>{menuTitle}</p>
    </Link>
  )
}

export default SidebarMenuCard