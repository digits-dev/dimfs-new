import { router } from '@inertiajs/react';
import React, { useRef, useState, useEffect } from 'react'
import { useTheme } from '../../Context/ThemeContext';
import useThemeStyles from '../../Hooks/useThemeStyles';

const BreadCrumbs = ({data, title}) => {
    const {theme} = useTheme();
    const { iconThemeColor } = useThemeStyles(theme);
    const [icon, setIcon] = useState(null);
    useEffect(() => {
        if ((data.module[0].name ?? title) === 'Dashboard') {
            setIcon('tachometer-alt-fast');
        } else if ((data.module[0].name ?? title) === 'Profile') {
            setIcon('id-card-alt');
        }else if ((data.module[0].name ?? title) === 'Change Password') {
            setIcon('user-lock');
        } else {
            setIcon(null);
        }
    }, [data.module, title]); 
  return (
   <>
    <div className="flex flex-col md:flex-row lg:flex-row justify-between py-1 font-poppins mb-3">
        <div className="space-x-2 mb-2">
            {icon ? 
                <i className={`fa fa-${icon} text-2xl ${iconThemeColor}`}></i>
            :
                <i className={data.module[0].icon+` text-2xl ${iconThemeColor}`}></i>
            }
            
            <span className={`text-2xl ${theme === 'bg-skin-black' ? 'text-gray-400': ''} `}>{title}</span>
        </div>
        <div className={`flex items-center  ${theme === 'bg-skin-black' ? 'text-gray-300' : 'text-black'} text-xs space-x-2`}>
            <i className={`fa fa-tachometer-alt-fast text-xs ${iconThemeColor}`}></i>
            <span>Home</span>
            <span>&gt;</span>
            <span className="text-gray-500">{data.module[0].name ?? title}</span>
        </div>
    </div>
   </>
  )
}


export default BreadCrumbs