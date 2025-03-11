import { Head, Link } from '@inertiajs/react'
import React from 'react'
import ContentPanel from '../../Components/Table/ContentPanel'
import { useTheme } from '../../Context/ThemeContext';
import { Badge, Calendar, Database, File, FileUp, Layers, ShoppingCart, Tag } from 'lucide-react';
import useThemeStyles from '../../Hooks/useThemeStyles';

const ItemMasterImportModules = ({page_title}) => {
    const { theme } = useTheme();
    const { pageTitle, pageSubTitle} = useThemeStyles(theme);

    const modules = [
        {
          id: 1,
          name: "Item Master Bulk Import",
          description: "NEW item creation bulk upload (MCB)",
          action: "NEW Item Import",
          type: "new",
          icon: <Database className="h-4 w-4" />,
          href: '/item_masters/item_master_import'
        },
        {
          id: 2,
          name: "SKU Legend/Segmentation Bulk Import",
          description: "Existing item SKU legend/Segmentation bulk update",
          action: "SKU Legend/Segmentation Import",
          type: "existing",
          icon: <Tag className="h-4 w-4" />,
          href: '/item_masters/item_master_import/sku_legend'
        },
        {
          id: 3,
          name: "SKU Status/Segmentation Bulk Import",
          description: "Existing item SKU status/Segmentation bulk update",
          action: "SKU Status/Segmentation Import",
          type: "existing",
          icon: <Tag className="h-4 w-4" />,
          href: '/item_masters/item_master_import/sku_status'
        },
        {
          id: 4,
          name: "WRR Date Bulk Import",
          description: "Existing item WRR date bulk update",
          action: "WRR Date Import",
          type: "existing",
          icon: <Calendar className="h-4 w-4" />,
          href: '/item_masters/item_master_import/wrr_date'
        },
        {
          id: 5,
          name: "ECOM Details Bulk Import",
          description: "Existing item ECOM details bulk update",
          action: "ECOM Details Import",
          type: "existing",
          icon: <ShoppingCart className="h-4 w-4" />,
          href: '/item_masters/item_master_import/ecom_details'
        },
        {
          id: 6,
          name: "Item Master Bulk Import (Accounting)",
          description: "Existing item master bulk update",
          action: "Update Item Import",
          type: "existing",
          icon: <Database className="h-4 w-4" />,
          href: '/item_masters/item_master_import/accounting'
        },
        {
          id: 7,
          name: "Item Master Bulk Import (MCB)",
          description: "Existing item master bulk update",
          action: "Update Item Import",
          type: "existing",
          icon: <Database className="h-4 w-4" />,
          href: '/item_masters/item_master_import/mcb'
        },
      ]


  return (
    <>
        <Head title={page_title}/>
        <ContentPanel>
            <div className='flex space-x-3 items-center'>
                <div className={`${theme} w-fit p-2 md:p-3 rounded-lg`}>
                    <Layers className="h-4 w-4 md:h-6 md:w-6 text-white" />
                </div>
                <div className='flex-1'>
                    <span className={`font-bold text-sm md:text-lg ${pageTitle}`}>Import Modules</span>
                    <p className={`${pageSubTitle} text-xs md:text-sm `}>Choose from the available modules below</p>
                </div>
                <Link href='/item_masters' className={`${theme} text-white text-xs px-2.5 py-1.5 md:px-3 md:py-2 rounded-lg border-black font-semibold hover:opacity-70`}>
                    Go Back
                </Link>
            </div>
            <div className={`border border-slate-200 mt-3 p-4 space-y-2 rounded-lg ${pageTitle} `}>
                {modules.map((module, index) => (
                    <div
                    key={module.id + index}
                    className={`flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-3 rounded-xl transition-all
                        border border-slate-200 hover:border-slate-200 hover:bg-slate-50/80 cursor-pointer
                        hover:shadow-lg
                    `}
                    >
                    <div className="flex items-start sm:items-center gap-4">
                        <div
                            className={`p-2 rounded-lg transition-all duration-200 shrink-0 ${module.type === "new" ? "bg-blue-100 text-blue-600" : "bg-amber-100 text-amber-600"}`}
                            >
                            {module.icon}
                        </div>

                        <div>
                        <div className="flex items-center gap-2 mb-1">
                            <p className="font-medium  text-xs md:text-sm">{module.name}</p>
                            {module.type === "new" ? (
                            <div className="px-2 py-0.5 font-semibold rounded-full text-[8px] md:text-[10px] text-white bg-blue-500 hover:bg-blue-600">NEW</div>
                            ) : (
                            <div className="px-2 py-0.5 font-semibold rounded-full text-[8px] md:text-[10px] text-amber-600 border-amber-300 bg-amber-100">
                                Existing
                            </div>
                            )}
                        </div>
                        <p className={`${pageSubTitle} text-[10px] md:text-xs`}>{module.description}</p>
                        </div>
                    </div>

                    <Link className="ml-auto" href={module.href}>
                        <div className={`flex items-center px-3 py-2 rounded-lg text-[10px] md:text-xs text-white transition-all shadow-sm whitespace-nowrap ${module.type === "new" ? "bg-blue-500 hover:bg-blue-600" : "bg-amber-500 hover:bg-amber-600"}`}>
                            <FileUp className="mr-2 h-3 w-3 md:h-4 md:w-4" />
                            {module.action}
                        </div>
                    </Link>
                    </div>
                ))}
            </div>
        </ContentPanel>
    </>
  )
}

export default ItemMasterImportModules