import React from 'react';
import Paginator from 'headless-pagination';
import { __PaginationPageSizeType, __PaginationProps } from './pagination.types';
import { __PaginationStyles as Styles } from './pagination.styles';
import { __PaginationOptionData } from './pagination.data';

// reexport page size options type for custom options list props
export interface PaginationPageSizeType extends __PaginationPageSizeType {}

// pagination component
const __PaginationComp: React.FC<__PaginationProps> = (props: __PaginationProps) => {
    // check required fields to run algorithms and renders
    if (!props.page || !props.pageSize || !props.total) return null;
    // calculate number of pages

    const paginator = new Paginator({
        totalItems: props.total, // required
        initialPage: props.page, // optional (default shown), page to show at start
        perPage: props.pageSize, // optional (default shown), how many items you're showing per page
        maxLinks: 7, // optional (default shown), max number of pagination links to show
    });

    const links = paginator.links();

    const options = props.options ? props.options : __PaginationOptionData;
    // if (Boolean(options.find((i) => i.value === props.pageSize))) options.push
    return (
        <div className={'flex items-center'}>
            {links.map((link, index) => {
                return (
                    <button
                        disabled={link.disabled}
                        className={Styles.button(link.active)}
                        key={index}
                        onClick={() => {
                            if (props.onChange) {
                                props.onChange(parseInt(link.label.toString()));
                            }
                        }}
                    >
                        {link.label}
                    </button>
                );
            })}

            <select
                className={Styles.select}
                onChange={(e) => {
                    if (props.onPageSizeChange) props.onPageSizeChange(Number(e.target.value));
                }}
                value={props.pageSize}
            >
                {options.map((i) => (
                    <option key={i.value} value={i.value}>
                        {i.title}
                    </option>
                ))}
            </select>
        </div>
    );
};

export default __PaginationComp;
