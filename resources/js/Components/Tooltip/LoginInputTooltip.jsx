import React from 'react'
import Tippy from '@tippyjs/react';
import 'tippy.js/dist/tippy.css';

const LoginInputTooltip = ({children, content = 'To change tooltip content add "content" attribute'}) => {
  return (
    <Tippy className='text-white rounded' content={content} placement="top">
        {children}
    </Tippy>
  )
}

export default LoginInputTooltip