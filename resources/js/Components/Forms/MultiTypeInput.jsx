import React from 'react'
import InputComponent from '../Forms/Input';
import CustomSelect from '../Dropdown/CustomSelect';
import { displayName } from 'react-quill';

const MultiTypeInput = ({type, name, value, disabled, placeholder, onChange, selectInputOptions = [], menuPlacement, displayName}) => {
  return (
    <>
        {type == 'text' ? 
            <InputComponent
                name={name}
                value={value}
                type='text'
                displayName={displayName}
                disabled={disabled}
                placeholder={placeholder}
                onChange={onChange}
            />
        :
            type == 'date' ? 
            <InputComponent
                name={name}
                value={value}
                displayName={displayName}
                type='date'
                disabled={disabled}
                placeholder={placeholder}
                onChange={onChange}
            />
        :   type == 'select' ? 
            <CustomSelect
                placeholder={placeholder}
                selectType="react-select"
                defaultSelect={placeholder}
                onChange={onChange}
                displayName={displayName}
                name={name}
                menuPlacement={menuPlacement}
                options={selectInputOptions}
                value={value}
            />
        : null
        }
    </>
  )
}

export default MultiTypeInput