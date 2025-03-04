import { Head, useForm } from "@inertiajs/react";
import React, { useState, useRef } from "react";
import { useTheme } from "../../Context/ThemeContext";
import { useToast } from "../../Context/ToastContext";
import ContentPanel from "../../Components/Table/ContentPanel";
import useThemeStyles from "../../Hooks/useThemeStyles";

const GashaponItemMastersImportView = ({
    page_title,
}) => {
 
  const [selectedFile, setSelectedFile] = useState(null);
  const [dragActive, setDragActive] = useState(false);
  const fileInputRef = useRef(null);

  const handleFileChange = (e) => {
    if (e.target.files && e.target.files.length > 0) {
      setSelectedFile(e.target.files[0]);
    }
  };

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

    return (
        <>
            <Head title={page_title} />
            <ContentPanel>
                <p className="text-lg font-semibold mb-2">
                    Upload Gashapon Item Master Data
                </p>
                <div className="border rounded-lg">
                    <div className="flex flex-col space-y-1.5 p-3 bg-gradient-to-r from-blue-50 to-blue-100 border-b">
                        <div className="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-blue-600"></i>
                        <h1 className="text-xl font-semibold leading-none tracking-tight text-blue-500">Import Guidelines</h1>
                        </div>
                        <p className="text-sm text-gray-500">Please review these important instructions before uploading your file</p>
                    </div>
                    <div className="px-4 py-3">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="flex items-center gap-3">
                                <i class="far fa-check-circle text-emerald-600"></i>
                                <div>
                                    <p className="text-sm font-bold text-gray-800">
                                    Column Dependencies
                                    </p>
                                    <p className="text-gray-600 text-sm">
                                    System will accept blank unless the columns have dependencies with each other.
                                    </p>
                                </div>
                            </div>
                            <div className="flex justify-items-center items-center gap-3">
                                <i class="far fa-check-circle text-emerald-600"></i>
                                <div>
                                    <p className="text-sm font-bold text-gray-800">
                                    Required Fields
                                    </p>
                                    <p className="text-gray-600 text-sm">
                                    If one of the ff needs to be updated e.g. (brand, wh category) <span className="font-semibold">
                                    BRAND, WH CATEGORY
                                    </span>  can't be null/blank.
                                    </p>
                                </div>
                            </div>
                            <div className="flex justify-items-center items-center gap-3">
                                <i class="far fa-check-circle text-emerald-600"></i>
                                <div>
                                    <p className="text-sm font-bold text-gray-800">
                                    Upload Limit
                                    </p>
                                    <p className="text-gray-600 text-sm">
                                    Upload limit up to 1,000 records per upload session.
                                    </p>
                                </div>
                            </div>
                            <div className="flex justify-items-center items-center gap-3">
                                <i class="far fa-check-circle text-emerald-600"></i>
                                <div>
                                    <p className="text-sm font-bold text-gray-800">
                                    File Format
                                    </p>
                                    <p className="text-gray-600 text-sm">
                                    File format should be: <div className="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground ml-1 font-mono bg-gray-100 mr-2">
                                        CSV
                                    </div>
                                    file format
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="grid md:grid-cols-2 gap-6 mt-4">
                    <div className="border rounded-lg">
                        <div className="flex flex-col py-3 px-6 space-y-1.5">
                            <div className="flex item-center justify-between">
                                <div className="flex items-center gap-2">
                                    <i class="fa-solid fa-download"></i>
                                    <h3 className="text-lg">
                                        Step 1: Get Template
                                    </h3>
                                </div>
                                <div className="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-blue-50 text-blue-700 border-blue-200">
                                    Required
                                </div>
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">
                                Download the CSV template with the correct column structure
                                </p>
                            </div>
                        </div>
                        <div className="p-4">
                            <p className="text-sm text-gray-500  mb-4">Use our pre-formatted template to ensure your data is structured correctly for import.</p>
                                <a href="/gashapon_item_masters/gashapon_template" 
                                className="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium h-10 px-4 py-2 w-full bg-blue-600 hover:bg-blue-700 text-white">
                                <i className="fa-solid fa-download"></i>
                                Download Template
                                </a>
                        </div>
                    </div>
                    <div className="border rounded-lg">
                        <div className="flex flex-col py-3 px-6 space-y-1.5">
                            <div className="flex item-center justify-between">
                                <div className="flex items-center gap-2">
                                    <i class="fa-solid fa-upload"></i>
                                    <h3 className="text-lg">
                                    Step 2: Upload File
                                    </h3>
                                </div>
                                <div className="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-purple-50 text-purple-700 border-purple-200">
                                    Required
                                </div>
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">
                                    Upload your completed CSV file
                                </p>
                            </div>
                        </div>
                        <div className="py-2 px-6">
                            {!selectedFile ? (
                                  <div 
                                  className={`border-2 border-dashed rounded-lg p-6 text-center ${
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
                                        <div className="bg-purple-100 px-3 py-2 rounded-full">
                                            <i class="fa-solid fa-upload"></i>
                                        </div>
                                        <p className="font-medium text-gray-700">Drag and drop your CSV file here</p>
                                        <p className="text-sm text-gray-500">or</p>
                                        <button
                                            className="cursor-pointer px-2 py-1 border border-gray-400 rounded-lg hover:bg-gray-200"
                                            onClick={handleBrowseClick}
                                            >
                                            Browse File
                                        </button>
                                        <p className="text-xs text-gray-500 mt-2">File type supported: CSV only</p>
                                      </div>
                                  </div>
                            ): (
                                <div className="border rounded-lg p-4">
                                <div className="flex items-center justify-between">
                                  <div className="flex items-center gap-3">
                                    <div className="bg-green-100 py-2 px-3 rounded-lg text-green-500">
                                        <i class="fa-regular fa-file-excel text-xl"></i>
                                    </div>
                                    <div>
                                      <p className="font-medium text-gray-800">{selectedFile.name}</p>
                                      <p className="text-xs text-gray-500">{(selectedFile.size / 1024).toFixed(2)} KB</p>
                                    </div>
                                  </div>
                                  <button onClick={removeFile} className="text-gray-500 hover:text-red-500 ">
                                     x
                                  </button>
                                </div>
                              </div>
                            )}  
                        </div>
                    </div>
                </div>
                <div className="flex justify-end gap-3 mt-3">
                    <button className="px-4 py-2 border border-gray-300 hover:bg-gray-100 rounded-lg">
                    Cancel
                    </button>
                    
                    <button
                        className={`px-4 py-2 border rounded-lg ${
                            !selectedFile ? "bg-green-500" : "bg-green-600"
                        } text-white  hover:bg-green-700`}
                        disabled={!selectedFile}
                        >
                    <i class="fa-solid fa-upload mr-2"></i>
                     Upload File
                    </button>

                </div>
            </ContentPanel>
        </>
    );
};

export default GashaponItemMastersImportView;
