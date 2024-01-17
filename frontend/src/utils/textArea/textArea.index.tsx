import React from 'react';
import { Controller } from 'react-hook-form';
import { Styles } from './textArea.styles';
import { __TextAreaProps } from './textArea.types';

const __TextAreaComp: React.FC<__TextAreaProps> = (props: __TextAreaProps) => {
    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => {
                return (
                    <div className={props.className}>
                        {/* input optional title */}
                        {props.title ? <span className={Styles.title(fieldState.invalid)}>{props.title}</span> : null}
                        <textarea
                            placeholder={props.placeholder}
                            {...field}
                            value={field.value || ''}
                            className={Styles.input(fieldState.invalid) + ' ' + (props.disabled ? Styles.disabled : '')}
                            disabled={props.disabled || false}
                        />
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

export default __TextAreaComp;
