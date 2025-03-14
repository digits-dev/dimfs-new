import { Head, useForm } from '@inertiajs/react'
import React, { useState } from 'react'
import ContentPanel from '../../Components/Table/ContentPanel'
import Button from '../../Components/Table/Buttons/Button'
import { useTheme } from '../../Context/ThemeContext'
import useThemeStyles from '../../Hooks/useThemeStyles'
import { StretchHorizontal } from 'lucide-react'
import InputComponent from '../../Components/Forms/Input'
import Modalv2 from '../../Components/Modal/Modalv2'
import { useToast } from '../../Context/ToastContext'

const ItemMasterAccountingApprovalView = ({ page_title, item_details, action}) => {

    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { textColorActive } = useThemeStyles(theme);
    const [confirmModal, setConfirmModal] = useState(false);

    const { data, setData, processing, reset, post, errors } = useForm({
        id: item_details.id,
        action: "",
    });

    
    const handleConfirmModalToggle = () => {
        setConfirmModal(!confirmModal);
    }

    const handleSubmit = () => {
        post("/item_master_accounting_approvals/approve_item", {
            onSuccess: (data) => {
                const { message, type } = data.props.auth.sessions;
                handleToast(message, type);
            },
            onError: (error) => {
                if (error && error.message) {
                    handleToast(error.message, "error");
                } else {
                    handleToast("An error occurred while uploading.", "error");
                }
            },
        });
    };

  return (
    <>
        <Head title={page_title} />
        <ContentPanel>
            <div className='flex space-x-3 items-center'>
           
                <span className={`font-semibold text-sm md:text-lg ${theme === 'bg-skin-black' ? ' text-white' : 'text-black/90'}`}>Item Details</span>
            </div>
            <div className="border p-4 rounded-lg mt-2">
                <div>
                    <div className="md:grid md:grid-cols-2 md:gap-2 space-y-2 md:space-y-0">
                        <InputComponent
                            displayName="Digits Code"
                            value={item_details.get_item.digits_code ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Brand Description"
                            value={item_details.get_brand.brand_description ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />

                        <InputComponent
                            displayName="Category Description"
                            value={item_details.get_category.category_description ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Margin Category Description"
                            value={item_details.get_margin_category.margin_category_description ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Store Cost"
                            value={item_details.store_cost ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Store Margin (%)"
                            value={item_details.store_cost_percentage ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="ECOMM - Store Cost"
                            value={item_details.ecom_store_cost ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="ECOMM - Store Margin (%)"
                            value={item_details.ecom_store_cost_percentage ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Landed Cost"
                            value={item_details.landed_cost ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Landed Cost Via SEA"
                            value={item_details.landed_cost_sea ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Actual Landed Cost"
                            value={item_details.actual_landed_cost ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Working Store Cost"
                            value={item_details.working_store_cost ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Working Store Margin (%)"
                            value={item_details.working_store_cost_percentage ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="ECOMM - Working Store Cost"
                            value={item_details.ecom_working_store_cost ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="ECOMM - Working Store Margin (%)"
                            value={item_details.ecom_working_store_cost_percentage ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Working Landed Cost"
                            value={item_details.working_landed_cost ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Effective Date"
                            value={item_details.effective_date ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Duration From"
                            value={item_details.duration_from ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Duration To"
                            value={item_details.duration_to ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                        <InputComponent
                            displayName="Support Type"
                            value={item_details.get_support_type?.support_type_description ?? '-'}
                            disabled={true}
                            onChange={()=>{}}
                        />
                    </div>
                    <div className="flex justify-between mt-4">
                        <Button
                            type="link"
                            href="/item_master_accounting_approvals"
                            extendClass={`${
                                theme === "bg-skin-white"
                                    ? primayActiveColor
                                    : theme
                            }`}
                            fontColor={textColorActive}
                        >
                            <span>Back</span>
                        </Button>
                        {action === "edit" && (
                            <div className="inline-flex gap-1">
                                <Button
                                    type="button"
                                    onClick={() => {
                                        setData("action", "reject");
                                        handleConfirmModalToggle();
                                    }
                                    }
                                    extendClass={"bg-red-500 border-none"}
                                    fontColor={textColorActive}
                                    disabled={processing}
                                >
                                    {processing ? (
                                        "Processing..."
                                    ) : (
                                        <span>
                                            <i
                                                className={`fa-solid fa-times mr-1`}
                                            ></i>
                                            REJECT
                                        </span>
                                    )}
                                </Button>

                                <Button
                                    type="button"
                                    onClick={() =>{
                                        setData("action", "approve")
                                        handleConfirmModalToggle();
                                    }
                                    }
                                    extendClass={"bg-green-500 border-none"}
                                    fontColor={textColorActive}
                                    disabled={processing}
                                >
                                    {processing ? (
                                        "Processing..."
                                    ) : (
                                        <span>
                                            <i
                                                className={`fa-solid fa-check mr-1`}
                                            ></i>
                                            APPROVE
                                        </span>
                                    )}
                                </Button>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </ContentPanel>
        <Modalv2
            isOpen={confirmModal} 
            setIsOpen={handleConfirmModalToggle}
            title="Confirmation"
            confirmButtonColor={data.action == "approve" ? 'bg-green-500' : 'bg-red-500'}
            confirmButtonName={data.action == "approve" ? 'Approve' : 'Reject'}
            content={`Are you sure you want to ${data.action} this item?`}
            onConfirm={handleSubmit}
        />
    </>
  )
}

export default ItemMasterAccountingApprovalView 