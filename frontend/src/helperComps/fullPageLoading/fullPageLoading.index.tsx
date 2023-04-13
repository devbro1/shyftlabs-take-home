import React from 'react';
import { FaSpinner } from 'react-icons/fa';
import { __FullPageLoadingProps } from './fullPageLoading.types';

// full page cover loading
const __FullPageLoadingComp: React.FC<__FullPageLoadingProps> = (props: __FullPageLoadingProps) => {
    return (
        <div className="w-full h-full fixed flex flex-col justify-center items-center top-0 left-0 bg-white opacity-75 z-50">
            <span className="text-indigo-500 opacity-75 block">
                <FaSpinner size={48} className="animate-spin 5-x" />
            </span>
            <p className="text-gray-600 dark:text-gray-400 mt-5">
                {props.message ? props.message : 'Loading, please wait.'}
            </p>
        </div>
    );
};

export default __FullPageLoadingComp;
