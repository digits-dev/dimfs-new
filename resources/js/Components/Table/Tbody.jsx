import React from 'react'
import useViewport from '../../Hooks/useViewport';
import { useTheme } from '../../Context/ThemeContext';

const Tbody = ({data, children}) => {
    const { width } = useViewport();
    const {theme} = useTheme();
    const mobileView = width < 640 ? true : false ;

  return (
   <>
    {data?.length != 0 ?  
        <tbody>
            {children}
        </tbody>
        : <tbody>
            <div className={`${theme === 'bg-skin-black' ? 'bg-skin-black' : 'bg-white'} absolute flex  w-full h-52 items-center justify-center`}>
                <img className='w-16 h-16 mr-2 mb-5' src='/images/others/empty-box.png'/>
                <p className='font-poppins mb-2 font-medium text-gray-400'>No Data Available</p>
            </div>
        </tbody>}
    </>
  )
}

export default Tbody