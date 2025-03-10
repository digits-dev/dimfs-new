import React, { useEffect, useState } from "react";
import { DragDropContext, Droppable, Draggable } from "@hello-pangea/dnd";
import ContentPanel from "../../Components/Table/ContentPanel";
import { useTheme } from "../../Context/ThemeContext";
import useThemeStyles from "../../Hooks/useThemeStyles";
import Button from "../../Components/Table/Buttons/Button";
import DropdownSelect from "../../Components/Dropdown/Dropdown";
import { Head, useForm } from "@inertiajs/react";
import axios from "axios";
import LoadingIcon from "../../Components/Table/Icons/LoadingIcon";
import Modalv2 from "../../Components/Modal/Modalv2";
import { useToast } from "../../Context/ToastContext";

const ModuleHeadersSort = () => {
    const { theme } = useTheme();
    const { handleToast } = useToast();
    const { textColorActive, borderTheme, sideBarBgColor } = useThemeStyles(theme);
    const [loading, setLoading] = useState(false);
    const [showModal, setShowModal] = useState(false);

    const { data, setData, processing, reset, post, errors } = useForm({
        module_id: "",
        module_name: "",
        items: [],
    });

    const modules = [
        {
            id: "38",
            name: "Item Master",
        },
        {
            id: "28",
            name: "Gashapon Item Master",
        },
        {
            id: "79",
            name: "RMA Item Master",
        },
    ];

    useEffect(() => {
        if (data.module_id) {
            setLoading(true);
            axios
                .get(`/module_headers/get_header/${data.module_id}`)
                .then((response) => {
                    setData("items", response.data)
                })
                .catch((error) => {
                    console.error("Error fetching module data:", error);
                    setError("Failed to load data");
                })
                .finally(() => {
                    setLoading(false);
                });
        }
    }, [data.module_id]);

  const handleDragEnd = (result) => {
    if (!result.destination) return; 

    const newItems = [...data.items];
    const [movedItem] = newItems.splice(result.source.index, 1);
    newItems.splice(result.destination.index, 0, movedItem);

    setData("items", newItems);
  };

  const handleModalToggle = () => {
    setShowModal(!showModal);
  }

  const handleSubmit = () => {
    handleModalToggle();
    post("/module_headers/sort", {
        onSuccess: (data) => {
            const { message, type } = data.props.auth.sessions;
            handleToast(message, type);
        },
        onError: (data) => {
            const { message, type } = data.props.auth.sessions;
            handleToast(message, type);
        },
    });
  };

  return (
    <>
        <Head title="Module Headers - Sort"/>
        <ContentPanel>
            <p className={`text-lg font-semibold mb-2 ${theme === 'bg-skin-black' ? ' text-white' : 'text-gray-700'}`}>Sort Headers</p>
            <div className="space-y-4">
                <div className={`flex flex-col md:flex-row justify-center items-stretch gap-4 h-full ${theme === 'bg-skin-black' ? ' text-white' : 'text-gray-700'}`}>
                    {/* CARD 1 */}
                    <div className="md:w-[40%] flex flex-col min-h-full border rounded-lg p-4 border-gray-300">
                        <DropdownSelect
                            placeholder="Select Module"
                            selectType="react-select"
                            defaultSelect="Select Module"
                            onChange={(selectedOption) =>
                                setData((prevData) => ({
                                    ...prevData,
                                    module_id: selectedOption?.value,
                                    module_name: selectedOption?.label,
                                    checked_items: [],
                                }))
                            }
                            name="module_name"
                            options={modules}
                            value={
                                data.module_id
                                    ? {
                                            label: data.module_name,
                                            value: data.module_id,
                                        }
                                    : null
                            }
                        />
                        {errors.module_name && (
                            <div className="font-poppins text-xs font-semibold mt-2 text-red-600">
                                {errors.module_name}
                            </div>
                        )}

                        
                    </div>

                    {/* CARD 2 */}
                    <div className="md:w-[60%] flex-1  flex flex-col min-h-full border rounded-lg p-4 border-gray-300">
                        <p className="font-semibold mb-2">Sorting</p>
                        
                        {loading ? ( 
                            <>
                                <div className="w-full h-52 flex items-center justify-center">
                                    <LoadingIcon />
                                </div>
                            </>

                        )
                        :
                        (
                            <>
                                {data.items.length !== 0 ? 
                                    <div className="overflow-hidden overflow-y-auto max-h-80 scrollbar-none">
                                        <DragDropContext onDragEnd={handleDragEnd}>
                                            <Droppable droppableId="list">
                                            {(provided) => (
                                                <div
                                                ref={provided.innerRef}
                                                {...provided.droppableProps}
                                                className="space-y-2"
                                                >
                                                {data.items.map((item, index) => (
                                                    <Draggable key={item} draggableId={item} index={index}>
                                                    {(provided, snapshot) => (
                                                        <div className={`${theme === 'bg-skin-black' ? ' bg-login-bg-color' : 'bg-white'} select-none flex items-center p-3 border bg-white rounded-lg`}  ref={provided.innerRef} {...provided.draggableProps}>
                                                            <span className="cursor-grab text-gray-400 text-xs hover:text-gray-400/70 mr-3 " {...provided.dragHandleProps}>
                                                                <i className="fa-solid fa-grip-vertical"></i>
                                                            </span>
                                                            <div className={`p-2 flex items-center rounded-lg ${theme} mr-3`}>
                                                                <i className={`fa-solid fa-border-none text-white text-[10px]`}></i>    
                                                            </div>
                                                            <div className='flex-1 flex-col'>
                                                                <p className={`${theme === 'bg-skin-black' ? ' text-white' : 'text-black/80'} mb-1 font-semibold text-xs`}>{item}</p>
                                                            </div>
                                                        </div>
                                                    )}
                                                    </Draggable>
                                                ))}
                                                {provided.placeholder}
                                                </div>
                                            )}
                                            </Droppable>
                                        </DragDropContext>
                                    </div>
                                    : 
                                        data.items.length === 0 && data.module_id
                                    ?
                                    <div className="select-none w-full h-52 flex items-center justify-center border border-dashed rounded-lg border-gray-300">
                                        <p className="font-semibold text-gray-300">
                                            Module is Empty
                                        </p>
                                    </div>
                                    :
                                    <div className="select-none w-full h-52 flex items-center justify-center border border-dashed rounded-lg border-gray-300">
                                        <p className="font-semibold text-gray-300">
                                            Please Select Module
                                        </p>
                                    </div>
                                
                                }
                            </>
                        )}
                        
                        
                    </div>
                </div>
                <div className="flex justify-between">
                    <Button
                        type="link"
                        href="/module_headers"
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
                        <span><i className={`fa-solid fa-arrow-up-1-9 mr-1`}></i>{" "}Sort Headers</span>
                    </Button>
                </div>
            </div>
        </ContentPanel>
        <Modalv2
            title="Confirmation"
            content="Do you want to sort headers?"
            isOpen={showModal}
            confirmButtonName="Sort Headers"
            setIsOpen={handleModalToggle}
            onConfirm={handleSubmit}
        />
    </>
  );
};

export default ModuleHeadersSort;
