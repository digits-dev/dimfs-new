import { Head, Link, useForm } from "@inertiajs/react";
import React, { useState, useRef } from "react";
import { useToast } from "../../../Context/ToastContext";
import ContentPanel from "../../../Components/Table/ContentPanel";
import { useTheme } from "../../../Context/ThemeContext";
import Button from "../../../Components/Table/Buttons/Button";
import { Check, Database, Download, Info, Upload, X } from "lucide-react";
import useThemeStyles from "../../../Hooks/useThemeStyles";
import Modalv2 from "../../../Components/Modal/Modalv2";

const ItemMasterBulkImport = ({page_title}) => {
  const { handleToast } = useToast();
  const { theme } = useTheme();
  const { pageTitle, pageSubTitle } = useThemeStyles(theme);
  const [selectedFile, setSelectedFile] = useState(null);
  const [dragActive, setDragActive] = useState(false);
  const fileInputRef = useRef(null);
  const [confirmModal, setConfirmModal] = useState(false);
  const { data, setData, post, processing, errors, reset } = useForm({
    file: null,
  });

  const handleFileChange = (e) => {
    if (e.target.files && e.target.files.length > 0) {
      setSelectedFile(e.target.files[0]);
      setData("file", e.target.files[0]);
    }
  };

  const handleConfirmModalToggle = () => {
    setConfirmModal(!confirmModal);
  }

  const handleDrag = (e) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === "dragenter" || e.type === "dragover") {
      setDragActive(true);
    } else if (e.type === "dragleave") {
      setDragActive(false);
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);

    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
      setSelectedFile(e.dataTransfer.files[0]);
      setData("file", e.dataTransfer.files[0]);
    }
  };

  const removeFile = () => {
    setSelectedFile(null);
  };

  const handleBrowseClick = () => {
    if (fileInputRef.current) {
      fileInputRef.current.click(); 
    }
  };

  const handleSubmit = () => {
    post("/item_masters/import_item_master", {
        forceFormData: true,
        onSuccess: (data) => {
            const { message, type } = data.props.auth.sessions;
            handleToast(message, type);
            removeFile();
            reset();
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
            <div>
                <div className='flex space-x-3 items-center'>
                    <div className={`${theme} w-fit p-2 md:p-3 rounded-lg`}>
                        <Database className="h-4 w-4 md:h-6 md:w-6 text-white" />
                    </div>
                    <span className={`font-bold text-sm md:text-lg ${theme === 'bg-skin-black' ? ' text-white' : 'text-black/90'}`}>Item Master Bulk Import</span>
                </div>

                <div className="bg-blue-50 border-blue-200 border rounded-lg p-4 mt-4">
                    <div className="flex items-start gap-3">
                    <Info className="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" />
                    <div>
                        <h2 className="text-base font-semibold text-blue-700">Import Guidelines</h2>
                        <p className="text-blue-700 text-sm">Please review these important instructions before uploading your file</p>
                    </div>
                    </div>
                </div>
                
                <div className="grid md:grid-cols-2 gap-3 mt-3 select-none">
                    <div className="p-4 border-l-4 border-l-green-500 border rounded-lg">
                        <div className="flex gap-3">
                            <div className="bg-green-100 rounded-full p-1 h-6 w-6 flex items-center justify-center flex-shrink-0">
                                <Check className="h-4 w-4 text-green-600" />
                            </div>
                            <div>
                            <p className={`${pageTitle} font-semibold text-sm`}>Data Consistency</p>
                            <p className={`${pageSubTitle} text-muted-foreground text-xs`}>
                                Ensure all records for uploading have corresponding values/entries in all submasters
                            </p>
                            </div>
                        </div>
                    </div>

                    <div className="p-4 border-l-4 border-l-green-500 border rounded-lg">
                        <div className="flex gap-3">
                            <div className="bg-green-100 rounded-full p-1 h-6 w-6 flex items-center justify-center flex-shrink-0">
                            <Check className="h-4 w-4 text-green-600" />
                            </div>
                            <div>
                            <p className={`${pageTitle} font-semibold text-sm`}>Handling N/A Sizes</p>
                            <p className={`${pageSubTitle} text-muted-foreground text-xs`}>
                                If "size" is N/A, set size = 0 and size code = N/A
                            </p>
                            </div>
                        </div>
                    </div>

                    <div className="p-4 border-l-4 border-l-green-500 border rounded-lg">
                        <div className="flex gap-3">
                            <div className="bg-green-100 rounded-full p-1 h-6 w-6 flex items-center justify-center flex-shrink-0">
                            <Check className="h-4 w-4 text-green-600" />
                            </div>
                            <div>
                            <p className={`${pageTitle} font-semibold text-sm`}>Default Codes</p>
                            <p className={`${pageSubTitle} text-muted-foreground text-xs`}>Enter <b>IMEI</b> or <b>SERIAL</b> for Device UID, <b>1</b> or <b>0</b> for Product Type</p>
                            </div>
                        </div>
                    </div>

                    <div className="p-4 border-l-4 border-l-green-500 border rounded-lg">
                        <div className="flex gap-3">
                            <div className="bg-green-100 rounded-full p-1 h-6 w-6 flex items-center justify-center flex-shrink-0">
                            <Check className="h-4 w-4 text-green-600" />
                            </div>
                            <div>
                            <p className={`${pageTitle} font-semibold text-sm`}>File Format Requirement</p>
                            <p className={`${pageSubTitle} text-muted-foreground text-xs`}>
                                File format should be: <span variant="outline" className="bg-gray-200 text-black font-medium px-1.5 rounded-full w-fit">CSV</span> file format for uploads
                            </p>
                            </div>
                        </div>
                    </div>
                </div>


                <div className="grid md:grid-cols-2 gap-3 mt-3">
                    <div className="border rounded-lg">
                        <div className="flex flex-col py-3 space-y-1.5 border-b mx-3">
                            <div className="flex item-center justify-between p-2">
                                <div className={`${pageTitle} flex items-center gap-2`}>
                                    <Download className="h-5 w-5" />
                                    <p className="text-base font-semibold">
                                        Step 1: Get Template
                                    </p>
                                </div>
                                <div className="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-blue-50 text-blue-700 border-blue-200">
                                    Required
                                </div>
                            </div>
                            
                        </div>
                        <div className="p-4">
                            <p className={`${pageTitle} text-sm font-semibold`}>
                                Download the CSV template with the correct column structure
                            </p>
                            <p className={`${pageSubTitle} mt-2 text-xs mb-4`}>Use our pre-formatted template to ensure your data is structured correctly for import.</p>
                            <a href="/item_masters/item_master_template" 
                                className={`${theme} inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium mt-2 px-4 py-2 w-full hover:opacity-70 text-white`}>
                                <Download className="h-5 w-5" />
                                Download Template
                            </a>
                        </div>
                    </div>

                    <div className="border rounded-lg">
                        <div className="flex flex-col py-3 space-y-1.5 border-b mx-3">
                            <div className="flex item-center justify-between p-2">
                                <div className={`${pageTitle} flex items-center gap-2`}>
                                    <Upload className="h-5 w-5" />
                                    <p className="text-base font-semibold">
                                        Step 2: Upload File
                                    </p>
                                </div>
                                <div className="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-blue-50 text-purple-700 border-purple-200">
                                    Required
                                </div>
                            </div>
                            
                        </div>
                        <div className="py-2 px-6">
                            <p className={`${pageTitle} text-xs mt-2 font-semibold`}>Upload your completed CSV file</p>
                          
                            {!selectedFile ? (
                                  <div 
                                  className={`border-2 border-dashed rounded-lg my-3 p-6 text-center ${
                                    dragActive ? "border-purple-500 bg-purple-50" : "border-gray-300"
                                  }`}
                                    onDragEnter={handleDrag}
                                    onDragLeave={handleDrag}
                                    onDragOver={handleDrag}
                                    onDrop={handleDrop}
                                  >
                                      <input 
                                        ref={fileInputRef}
                                        className="hidden"
                                        id="file-upload"
                                        accept=".csv"
                                        type="file"
                                        onChange={handleFileChange}
                                      />
                                      <div className="flex flex-col items-center gap-2">
                                        <div className="bg-purple-100 p-3 rounded-full">
                                            <Upload className="h-5 w-5 text-purple-500" />
                                        </div>
                                        <p className={`${pageSubTitle} font-sm`}>Drag and drop your CSV file here</p>
                                        <p className={`text-sm ${pageSubTitle}`}>or</p>
                                        <button
                                            className="cursor-pointer px-2.5 py-1.5 text-sm bg-gray-200 rounded-lg hover:bg-gray-200"
                                            onClick={handleBrowseClick}
                                            >
                                            Browse File
                                        </button>
                                        <p className={`${pageSubTitle} text-xs mt-2`}>File type supported: CSV only</p>
                                      </div>
                                  </div>
                            ): (
                                <div className="border rounded-lg p-4 my-3">
                                    <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-3">
                                        <div className="bg-green-100 py-2 px-3 rounded-lg text-green-500">
                                            <i className="fa-regular fa-file-excel text-xl"></i>
                                        </div>
                                        <div>
                                        <p className={`text-sm font-medium ${pageTitle}`}>{selectedFile.name}</p>
                                        <p className={`text-xs ${pageSubTitle}`}>{(selectedFile.size / 1024).toFixed(2)} KB</p>
                                        </div>
                                    </div>
                                    <button onClick={removeFile} className="text-gray-500 hover:text-red-500 ">
                                       <X className="h-4 w-4"/>
                                    </button>
                                    </div>
                                {errors.file && <p className="text-red-500 text-sm mt-2">{errors.file}</p>}
                              </div>
                            )}  
                        </div>
                    </div>
                </div>
             

                <div className="flex justify-end gap-1.5 mt-3">
                    <Button
                        type="link"
                        href="/item_masters/import_modules"
                        extendClass={`${theme}`}
                        fontColor="text-white"
                        disabled={processing}
                    >
                            <span>Cancel</span>
                    </Button>

                    <Button
                        type="button"
                        onClick={()=>{handleConfirmModalToggle()}}
                        extendClass={`px-4 py-2 border-none rounded-lg ${!selectedFile ? "bg-green-500" : "bg-green-600"} text-white hover:opacity-70`}
                        fontColor="text-white"
                        disabled={!selectedFile}
                    >
                          <Upload className="h-5 w-5 mr-1"/> <span>Upload File</span>
                    </Button>
                </div>
                </div>
            </ContentPanel>
            <Modalv2
                isOpen={confirmModal} 
                setIsOpen={handleConfirmModalToggle}
                title="Confirmation"
                confirmButtonName="Import"
                content="Are you sure you want to Import this file?"
                onConfirm={handleSubmit}
            />
        </>
    );
};

export default ItemMasterBulkImport;
