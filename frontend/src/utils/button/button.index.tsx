import React, { PropsWithChildren, useState } from 'react';
import { __ButtonProps } from './button.types';
import { __ButtonStyles as Styles } from './button.styles';
import { FaSpinner } from 'react-icons/fa';
import { Link } from 'react-router-dom';

// Button component
const __ButtonComp = (props: PropsWithChildren<__ButtonProps>) => {
    const [loading, setLoading] = useState<boolean>(false);

    function innerOnClickHandler(e: React.MouseEvent<HTMLAnchorElement | HTMLButtonElement, MouseEvent>) {
        e.preventDefault();
        e.stopPropagation();

        if (props.disabled) {
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        if (props.onClick) {
            const promise = props.onClick(e);
            // if onClick function return promise we should render loading, because the task is async
            if (promise) {
                setLoading(true);
                promise.then(() => {
                    setLoading(false);
                });
            }
        }
    }

    // button content (add loading spinner to content on loading state)
    const content = () => {
        return (
            <>
                {loading ? <FaSpinner className={Styles.spinner} /> : null}
                {props.children ? props.children : 'Submit'}
            </>
        );
    };
    // if button is a link and also link should be <a/> (not <Link/>)
    if (props.href && props.outOfRouter) {
        return (
            <a href={props.href} onClick={innerOnClickHandler} className={Styles.root(props.className)}>
                {content()}
            </a>
        );
    }
    // if button is a link and also link should be <Link/> (not <a/>)
    if (props.href) {
        return (
            <Link to={props.href} onClick={innerOnClickHandler} className={Styles.root(props.className)}>
                {content()}
            </Link>
        );
    }
    // if button is not <a/> and it's simple button with onclick callback
    return (
        <button
            disabled={loading || props.disabled}
            onClick={innerOnClickHandler}
            className={Styles.root(props.className)}
        >
            {content()}
        </button>
    );
};

export default __ButtonComp;
