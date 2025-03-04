import { Link } from "@inertiajs/react";
import Tippy from "@tippyjs/react";
import React from "react";
import { FaS } from "react-icons/fa6";
import 'tippy.js/dist/tippy.css';

const RowAction = ({ action, size, href, onClick, type = 'link', hasTooltip = false, tooltipContent, tooltipPlacement}) => {
	const iconSize = {
		sm: "h-4 w-4",
		md: "h-5 w-5",
		lg: "h-6 w-6",
	}[size];

	const icon = {
		view: <i className={`fa fa-eye p-2 bg-sky-600 text-white text-[12px] rounded-md hover:bg-sky-400 ${iconSize}`}></i>,
		delete:<i className={`fa fa-trash p-2 bg-red-500 text-white text-[12px] rounded-md hover:bg-red-400 ${iconSize}`}></i>,
		edit: <i className={`fa fa-edit p-2 bg-green-500 text-white text-[12px] rounded-md hover:bg-green-400 ${iconSize}`}></i>,
		add_segmentation: <i className={`fa fa-pen p-2 bg-yellow-500 text-white text-[12px] rounded-md hover:bg-yellow-400 ${iconSize}`}></i>,
	}[action];

	return (
	<Tippy className='text-white rounded' content={tooltipContent} placement={tooltipPlacement} zIndex={1000} disabled={!hasTooltip}>
		{type == 'button' ? 	
			<button className="relative m-[2px]" onClick={onClick}>
				{icon}
			</button> 
		: 
		<Link
			className="relative m-[2px]"
			as="button"
			href={href}
		>
			{icon}
		</Link>}
	</Tippy>
	);
};

export default RowAction;
