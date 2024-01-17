import React from 'react';
import { Controller } from 'react-hook-form';
import { __SelectStyles as Styles } from './select.styles';
import { __SelectOptionType, __SelectProps } from './select.types';

export interface SelectOptionType extends __SelectOptionType {}
// text input component compatible with controller logic
const __SelectComp: React.FC<__SelectProps> = (props: __SelectProps) => {
    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => {
                return (
                    <div className={props.className}>
                        {/* input optional title */}
                        {props.title ? <span className={Styles.title(fieldState.invalid)}>{props.title}</span> : null}
                        <select
                            disabled={props.disabled || !props.options?.length}
                            className={props.inputClassName || Styles.input(fieldState.invalid, Boolean(field.value))}
                            {...field}
                        >
                            {props.placeholder ? (
                                <option value="" disabled={props.unSelectable}>
                                    {props.placeholder}
                                </option>
                            ) : null}
                            {props.options?.map((item) => (
                                <option key={item.value} value={item.value}>
                                    {item.title}
                                </option>
                            ))}
                        </select>
                        {/* input error message */}
                        {fieldState.invalid && fieldState.error?.message ? (
                            <span className={Styles.error}>{fieldState.error?.message}</span>
                        ) : null}
                        {/* input optional description */}
                        {props.description ? <span className={Styles.description}>{props.description}</span> : null}
                    </div>
                );
            }}
        />
    );
};

export default __SelectComp;
