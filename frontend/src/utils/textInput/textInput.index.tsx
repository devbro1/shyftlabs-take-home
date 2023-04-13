import React from 'react';
import { Controller } from 'react-hook-form';
import { TextInputStyles as Styles } from './textInput.styles';
import { __TextInputProps } from './textInput.types';

// text input component compatible with controller logic
const __TextInputComp: React.FC<__TextInputProps> = (props: __TextInputProps) => {
    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => {
                return (
                    <div className={props.className}>
                        {/* input optional title */}
                        {props.title ? <span className={Styles.title(fieldState.invalid)}>{props.title}</span> : null}
                        <input
                            placeholder={props.placeholder}
                            type={props.type ? props.type : 'text'}
                            {...field}
                            className={Styles.input(fieldState.invalid)}
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

export default __TextInputComp;
