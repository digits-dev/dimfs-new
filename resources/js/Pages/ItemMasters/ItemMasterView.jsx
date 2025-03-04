import { Head, useForm } from '@inertiajs/react'
import React, { useEffect } from 'react'
import { useTheme } from "../../Context/ThemeContext";
import ContentPanel from '../../Components/Table/ContentPanel'
import MultiTypeInput from '../../Components/Forms/MultiTypeInput';
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from '../../Components/Table/Buttons/Button';
import InputComponent from '../../Components/Forms/Input';


const ItemMasterView = ({page_title, table_headers, item_master_detail}) => {
  const { theme } = useTheme();
  const { primayActiveColor, textColorActive } = useThemeStyles(theme);

  // console.log(item_master_detail);


  return (
    <>
      <Head title={page_title}/>
      <ContentPanel>
        <p className="text-lg font-semibold mb-2">Item Details</p>
        <div className='border p-4 rounded-lg'>
          {table_headers &&
            <div>
              <div className="grid grid-cols-2 gap-2">
                {table_headers.map((input, index) => {
                  const value = input.table_join
                      ? input.table_join.split('.').reduce((acc, key) => acc?.[key], item_master_detail)
                      : item_master_detail[input.name];

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
              
              {item_master_detail.get_item_segmentations != 0 && 
                <div className='mt-5'>
                  <p className="text-base font-semibold mb-2">Segmentations</p>
                  <div className="grid grid-cols-2 gap-2 mt-2 border rounded-lg p-3">
                  {item_master_detail.get_item_segmentations.map((item, index) => (
                      <InputComponent
                        key={index}
                        disabled={true}
                        displayName={item.get_segmentation?.segmentation_description}
                        value={item.get_sku_legend?.sku_legend_description ?? "-"} 
                        onChange={()=>{}}                  
                    />
                    ))
                  }
                  </div>
                </div>
              
              }
              
              <div className="flex justify-between mt-4">
                  <Button
                      type="link"
                      href="/item_masters"
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

export default ItemMasterView