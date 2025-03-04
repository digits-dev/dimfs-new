import { Link, router } from "@inertiajs/react";
import React, { useState } from "react";

const Button = ({
    children,
    onClick,
    disabled,
    extendClass,
    type = "button",
    href,
    fontColor
}) => {
    const [loading, setLoading] = useState(false);
        
    router.on("start", () => setLoading(true));
    router.on("finish", () => setLoading(false));

    return (

        <>
            {type == "button" ? (
                <button
                    onClick={onClick}
                    disabled={loading}
                    className={`${fontColor} overflow-hidden border border-gray-500 rounded-md font-poppins text-sm px-2 py-2 hover:opacity-80 ${extendClass}`}
                >
                    {children}
                </button>
            ) : (
                loading ? (
                    <span
                        className={`${fontColor} pt-2 overflow-hidden border border-gray-500 rounded-md font-poppins text-sm px-2 py-2 opacity-70 cursor-not-allowed ${extendClass}`}
                    >
                        {children}
                    </span>
                ):
                (
                    <Link
                        href={href}
                        className={`${fontColor} pt-2 overflow-hidden border border-gray-500 rounded-md font-poppins text-sm px-2 py-2 hover:opacity-80 ${extendClass}`}
                    >
                        {children}
                    </Link>
                )
            )}
        </>
    );
};

export default Button;
