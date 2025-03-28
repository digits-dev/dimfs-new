import { Head, useForm } from '@inertiajs/react'
import React, { useEffect } from 'react'
import { useTheme } from "../../Context/ThemeContext";
import ContentPanel from '../../Components/Table/ContentPanel'
import MultiTypeInput from '../../Components/Forms/MultiTypeInput';
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from '../../Components/Table/Buttons/Button';
import InputComponent from '../../Components/Forms/Input';


const GashaponItemMastersView = ({page_title, table_headers, gashapon_item_master_detail}) => {
  const { theme } = useTheme();
  const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

  return (
    <>
      <Head title={page_title}/>
      <ContentPanel>
        <p className="text-lg font-semibold mb-2">Gashapon Item Details</p>
        <div className='border p-4 rounded-lg'>
          {table_headers &&
            <div>
              <div className="grid grid-cols-2 gap-2">
                {table_headers.map((input, index) => {
                  const value = input.table_join
                      ? input.table_join.split('.').reduce((acc, key) => acc?.[key], gashapon_item_master_detail)
                      : gashapon_item_master_detail[input.name];

                  return (
                    <InputComponent
                      key={index}
                      disabled={true}
                      displayName={input.header_name}
                      value={value ?? "-"} 
                      onChange={()=>{}}                  
                    />
                  );
                })}
              </div>
              
              <div className="flex justify-between mt-4">
                  <Button
                      type="link"
                      href="/gashapon_item_masters"
                      extendClass={`${theme === "bg-skin-white"? primayActiveColor: theme}`}
                      fontColor={textColorActive}
                  >
                    <span>Back</span>
                  </Button>
              </div>
            </div>
          }
        </div>
        
        
          
      </ContentPanel>
    </>
   
  )
}

export default GashaponItemMastersView