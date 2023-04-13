import React, { useState } from 'react';
import { IoClose, IoInformationCircle } from 'react-icons/io5';
import { BiMessageAltError } from 'react-icons/bi';
import { RiErrorWarningLine } from 'react-icons/ri';
import { BsCheckCircle } from 'react-icons/bs';
import { __AlertStyles as style } from './Alert.style';

//https://flowbite.com/docs/components/alerts/
function __AlertComp(props: any) {
    const [isClosing, setIsClosing] = useState(false);
    let styler = style.info;
    let icon = <IoInformationCircle />;
    const closing = isClosing ? ' animate-fadeOut' : '';
    //transition-all duration-300 ease-in opacity-0
    if (props.type === 'success') {
        styler = style.success;
        icon = <BsCheckCircle />;
    } else if (props.type === 'warning') {
        styler = style.warning;
        icon = <RiErrorWarningLine />;
    } else if (props.type === 'error') {
        styler = style.error;
        icon = <BiMessageAltError />;
    }

    return (
        <div
            className={styler.wrapper + closing}
            role="alert"
            onAnimationEnd={() => {
                props.onClose();
            }}
        >
            {icon}
            <div className={styler.message}>{props.message}</div>
            <button
                type="button"
                className={styler.closeButton}
                data-dismiss-target="#alert-3"
                aria-label="Close"
                onClick={() => {
                    setIsClosing(true);
                }}
            >
                <span className="sr-only">Close</span>
                <IoClose />
            </button>
        </div>
    );
}

__AlertComp.defaultProps = {
    isClosing: false,
};

export default __AlertComp;
