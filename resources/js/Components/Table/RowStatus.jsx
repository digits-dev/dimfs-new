import React from 'react'
import { useTheme } from '../../Context/ThemeContext';

const RowStatus = ({
  children,
  systemStatus,
  isLoading,
  center,
  color,
  addClass,
  addStatusClass
}) => {

  const {theme} = useTheme();
  const systemStatusColor = {
      active: "bg-status-success",
      inactive: "bg-status-error",
  }[systemStatus];


  return (
    <td className={`${center && 'text-center'} px-6 py-3 ${addClass} ${theme === 'bg-skin-black' ? theme+' text-gray-300' : 'bg-white'}`}>
			{isLoading ? (
				<span className="animate-pulse inline-block w-3/4 rounded-lg h-4 p-auto bg-gray-200">&nbsp;&nbsp;</span>
			) : (
        <>
        {systemStatusColor ? 
          <div className={`mx-auto ${addStatusClass} rounded-full text-[12px] text-white px-[9px] py-[2px] ${systemStatusColor} `}>{children}</div> 
        :
				  <div style={{background: color}} className={`mx-auto ${addStatusClass} text-[12px] rounded-[9px] ${color && 'text-white'} px-3 py-1 `}>{children}</div>
        }
				
        </>
			)}
		</td>
  )
}

export default RowStatus
