import { Head, useForm } from '@inertiajs/react'
import React, { useEffect } from 'react'
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import ContentPanel from '../../Components/Table/ContentPanel'
import MultiTypeInput from '../../Components/Forms/MultiTypeInput';
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from '../../Components/Table/Buttons/Button';
import CustomSelect from '../../Components/Dropdown/CustomSelect';


const ItemMasterSegmentation = ({item_master_id, page_title, segmentation_inputs, sku_legend_options, item_segmentations}) => {
  const { theme } = useTheme();
  const { handleToast } = useToast();
  const { primayActiveColor, textColorActive, buttonSwalColor } = useThemeStyles(theme);

  const initialFormData = segmentation_inputs.reduce((acc, item) => {
    const item_segmentation = item_segmentations.find(seg => seg.get_segmentation.segmentation_column === item.segmentation_column);

    acc[item.segmentation_column] = item_segmentation ? {
          sku_legend_id: item_segmentation.get_sku_legend?.id,
          segmentation_id: item_segmentation.get_segmentation?.id,
      } : "";
    return acc;
  }, { validation: "", item_master_id: item_master_id });

  const { data, setData, processing, reset, post, errors } = useForm(initialFormData);

  // APPLYING VALIDATION
  useEffect(()=>{
    setData('validation', Object.fromEntries(
        segmentation_inputs.map((input) => [input.segmentation_column, 'required'])
    ));

  }, [])

  // ONCHANGE
  const handleInputChange = (name, selectedValue, segmentation_id) => {
          setData((prevData) => ({
            ...prevData,
            [name]: {
                sku_legend_id: selectedValue?.value,
                segmentation_id: segmentation_id,
            }
          }));
      
    };

  // FORM SUBMIT
  const handleFormSubmit = (e) => {
      e.preventDefault();
      Swal.fire({
          title: `<p class="font-poppins text-3xl" >Do you want to add Item?</p>`,
          showCancelButton: true,
          confirmButtonText: `Add Item`,
          confirmButtonColor: buttonSwalColor,
          icon: "question",
          iconColor: buttonSwalColor,
          reverseButtons: true,
      }).then(async (result) => {
          if (result.isConfirmed) {
              post("/item_masters/post_segmentation", {
                  onSuccess: (data) => {
                      const { message, type } = data.props.auth.sessions;
                      handleToast(message, type);
                  },
                  onError: (data) => {
                      const { message, type } = data.props.auth.sessions;
                      handleToast(message, type);
                  },
              });
          }
      });
  };

  return (
    <>
      <Head title={page_title}/>
      <ContentPanel>
        <p className="text-lg font-semibold mb-2">Item Segmentation</p>
        <div className='border p-4 rounded-lg'>
          {segmentation_inputs &&
            <form onSubmit={handleFormSubmit}>
              <div className="md:grid md:grid-cols-2 md:gap-2 space-y-2 md:space-y-0">
                {segmentation_inputs.map((input, index) => {

                  const item_segmentation = item_segmentations.find(seg => seg.get_segmentation.segmentation_column === input.segmentation_column);

                  return  <div key={index} className="w-full">
                            <CustomSelect
                              selectType='react-select'
                              name={input.segmentation_column}
                              value={item_segmentation ? sku_legend_options.find((option) => option.value === item_segmentation.get_sku_legend?.id) : ''}
                              onChange={(selectedValue) => handleInputChange(input.segmentation_column, selectedValue, input.id)}
                              displayName={input.segmentation_description}
                              placeholder={`Enter Segmentation`}
                              options={sku_legend_options ? sku_legend_options : []}
                            />
                            {(errors[input.segmentation_column]) && (
                                <div className="font-poppins text-xs mt-2 font-semibold text-red-600">
                                    {errors[input.segmentation_column]}
                                </div>
                            )}
                          </div>
                  
                })}
              </div>
              
              <div className="flex justify-between mt-4">
                  <Button
                      type="link"
                      href="/item_masters"
                      extendClass={`${theme === "bg-skin-white"? primayActiveColor: theme}`}
                      fontColor={textColorActive}
                      disabled={processing}
                  >
                    <span>Back</span>
                  </Button>
                  <Button
                      type="button"
                      extendClass={`${theme === "bg-skin-white"? primayActiveColor: theme}`}
                      fontColor={textColorActive}
                      disabled={processing}
                  >
                      {processing ? ("Create Table") : 
                      (
                          <span><i className={`fa-solid fa-plus mr-1`}></i>{" "}Update Item Segmentation</span>
                      )}
                  </Button>
              </div>
            </form>
          }
        </div>
        
        
          
      </ContentPanel>
    </>
   
  )
}

export default ItemMasterSegmentation