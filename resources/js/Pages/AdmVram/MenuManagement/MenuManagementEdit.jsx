import React from 'react'
import ContentPanel from '../../../Components/Table/ContentPanel'
import CustomSelect from '../../../Components/Dropdown/CustomSelect'
import { Head, useForm } from '@inertiajs/react'
import InputComponent from '../../../Components/Forms/Input'
import { useTheme } from '../../../Context/ThemeContext'
import Button from '../../../Components/Table/Buttons/Button'
import useThemeStyles from '../../../Hooks/useThemeStyles'
import { useToast } from '../../../Context/ToastContext'
import { DragDropContext, Droppable, Draggable } from "@hello-pangea/dnd";


const MenuManagementEdit = ({page_title, privileges, menu}) => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { textColorActive, borderTheme, sideBarBgColor } = useThemeStyles(theme);
    const { data, setData, processing, reset, post, errors } = useForm({
          id: "" || menu?.id,
          privilege: "",
          menu_name: "" || menu?.name,
          menu_type: "" || menu?.type,
          menu_icon: "" || menu?.icon,
          path: "" || menu?.path,
          slug: "" || menu?.slug,
          children: menu?.children,
      });


    console.log(data)
      
    const handleModalToggle = () => {
      setShowModal(!showModal);
    }

    const handleDragEnd = (result) => {
        if (!result.destination) return; 
    
        const newItems = [...data.children];
        const [movedItem] = newItems.splice(result.source.index, 1);
        newItems.splice(result.destination.index, 0, movedItem);
    
        setData("children", newItems);
      };

  return (
    <>
      <Head title={page_title}/>
      <ContentPanel>
          <div className='flex items-center'>
              <img src="/images/others/menu.png" className="w-6 h-6 cursor-pointer duration-500"/>
              <p className='font-bold text-black/90 text-lg ml-3'>Update Menu</p>
          </div>
          <div className={`flex flex-col md:flex-row justify-center items-stretch gap-4 mt-4 h-full ${theme === 'bg-skin-black' ? ' text-white' : 'text-gray-700'}`}>
            {/* CARD 1 */}
            <div className={`${data.children?.length !== 0 ? 'md:w-[50%]' : 'w-full'} flex flex-col space-y-2 min-h-full border rounded-lg p-4 border-gray-300`}>
                <CustomSelect
                    placeholder="Choose Privilege"
                    selectType="react-select"
                    defaultSelect="Select Privilege"
                    // onChange={(selectedOption) => setData((prevData) => ({
                    //     ...prevData,
                    //     id_adm_privileges: selectedOption?.value,
                    //     privilege_name: selectedOption?.label
                    // }))}
                    isMulti
                    name="privilege"
                    isStatus={false}
                    options={privileges}
                />
                <InputComponent
                    name="menu_name"
                    value={data.menu_name}
                    placeholder="Enter menu_name (if menu type is URL just add #)"
                    onChange={(e)=> setData("menu_name", e.target.value)}
                />
                {(errors.menu_name) && (
                    <div className="font-poppins text-xs font-semibold text-red-600">
                        {errors.menu_name}
                    </div>
                )} 
                
                <div className={`${data.children?.length !== 0 ? 'md:grid md:grid-cols-2 md:gap-2 md:space-y-0' : 'space-y-2'}`}>
                    <div>
                        <label className={`block text-sm font-bold ${theme === 'bg-skin-black' ? ' text-gray-400' : 'text-gray-700'}  font-poppins`}>Menu Type</label>
                        <div className="relative rounded-lg mt-1 flex space-x-1 overflow-hidden border-2 bg-gray-300">
                            <div
                                className={`absolute ${theme} rounded-md h-full w-1/2 transition-all duration-300 ${
                                data.menu_type === "Route" ? "left-0" : "left-1/2"}`}
                            >
                            </div>
                            <button
                                className={` flex-1 py-1 z-10 outline-none text-sm font-medium
                                ${data.menu_type === "Route" ? "text-white" : "text-black/50"}`}
                                onClick={() => setData("menu_type", "Route")}
                            >
                                Route
                            </button>
                            <button
                                className={`text-white flex-1 py-2 z-10 outline-none text-sm font-medium
                                ${data.menu_type === "URL" ? "text-white" : "text-black/50"}`}
                                onClick={() => setData("menu_type", "URL")}
                            >
                                URL
                            </button>
                        </div>
                    </div>
                    {(errors.menu_type) && (
                        <div className="font-poppins text-xs font-semibold text-red-600">
                            {errors.menu_type}
                        </div>
                    )}
                    <InputComponent
                        name="path"
                        addClass="mt-2"
                        value={data.path}
                        placeholder="Enter Path (if menu type is URL just add #)"
                        onChange={(e)=> setData("path", e.target.value)}
                    />
                    {(errors.path) && (
                        <div className="font-poppins text-xs font-semibold text-red-600">
                            {errors.path}
                        </div>
                    )} 
                </div>

                <div className={`${data.children?.length !== 0 ? 'md:grid md:grid-cols-2 md:gap-2 md:space-y-0' : 'space-y-2'}`}>
                <InputComponent
                    name="slug"
                    value={data.slug}
                    placeholder="Enter Slug (if menu type is URL leave it empty)"
                    onChange={(e)=> setData("slug", e.target.value)}
                />
                {(errors.slug) && (
                    <div className="font-poppins text-xs font-semibold text-red-600">
                        {errors.slug}
                    </div>
                )}

                <InputComponent
                    name="menu_icon"
                    addClass="mt-2"
                    value={data.menu_icon}
                    placeholder="Enter Icon (ex. fa-regular fa-circle)"
                    onChange={(e)=> setData("menu_icon", e.target.value)}
                />
                {(errors.menu_icon) && (
                    <div className="font-poppins text-xs font-semibold text-red-600">
                        {errors.menu_icon}
                    </div>
                )}
                </div>
                <div>
                    <div className="flex justify-between mt-5">
                        <Button
                            type="link"
                            href="/menu_management"
                            extendClass={`${theme}`}
                            fontColor={textColorActive}
                            disabled={processing}
                        >
                                <span>Back</span>
                        </Button>
                        <Button
                            type="button"
                            extendClass={`${theme}`}
                            fontColor={textColorActive}
                            disabled={processing}
                            onClick={handleModalToggle}
                        >
                            <span><i className={`fa-solid fa-edit mr-1`}></i>{" "}Update Menu</span>
                        </Button>
                    </div>
                </div>
                
            </div>
            {/* CARD 2 */}
            {data.children?.length !== 0 && 
            <div className="md:w-[50%] flex flex-col min-h-full border rounded-lg p-4 border-gray-300">
                <p className="font-semibold mb-2">Sorting</p>
                    
                        <div className="overflow-hidden overflow-y-auto max-h-80 scrollbar-none">
                            <DragDropContext onDragEnd={handleDragEnd}>
                                <Droppable droppableId="list">
                                {(provided) => (
                                    <div
                                    ref={provided.innerRef}
                                    {...provided.droppableProps}
                                    className="space-y-2"
                                    >
                                    {data.children.map((child, childIndex) => (
                                        <Draggable
                                            key={child.id}
                                            draggableId={child.name + childIndex}
                                            index={childIndex}
                                        >
                                            {(provided) => (
                                                <div className='flex items-center p-3 border bg-white rounded-lg'  ref={provided.innerRef} {...provided.draggableProps}>
                                                    <span className="cursor-grab text-gray-400 text-xs hover:text-gray-400/70 mr-3 " {...provided.dragHandleProps}>
                                                        <i className="fa-solid fa-grip-vertical"></i>
                                                    </span>
                                                    <div className={`p-2 flex items-center rounded-lg ${theme} mr-3`}>
                                                        <i className={`${child.icon} text-white text-[10px]`}></i>    
                                                    </div>
                                                    <div className='flex-1 flex-col'>
                                                        <p className='mb-1 font-semibold text-black/80 text-xs'>{child.name}</p>
                                                        <div className='flex flex-wrap gap-1'>
                                                            {child.get_menus_privileges && child.get_menus_privileges?.map((privilege, index)=>(
                                                                <p key={privilege.get_privilege?.name + index} className='text-[10px] text-nowrap bg-cyan-400 text-white px-2  py-0.5 rounded-full font-semibold'>{privilege.get_privilege?.name}</p>
                                                            ))}
                                                        </div>
                                                    </div>
                                                    <div className='flex items-center space-x-2'>
                                                        <div className='text-sm w-5 h-5 p-4 flex items-center justify-center hover:bg-gray-300 cursor-pointer group rounded-full '>
                                                            <i className="fa-solid fa-pen text-xs text-gray-400 group-hover:text-white "></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            )}
                                        </Draggable>
                                    ))}
                                    {provided.placeholder} {/* Ensures smooth dragging */}
                                    </div>
                                )}
                                </Droppable>
                            </DragDropContext>
                        </div>
                       
                     
                        
            </div>
            }
          </div>
      </ContentPanel>
    </>
  )
}

export default MenuManagementEdit