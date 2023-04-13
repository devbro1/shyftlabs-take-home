import React, { useState } from 'react';
import { Controller } from 'react-hook-form';
import { FaExchangeAlt } from 'react-icons/fa';
import { MultiSelectStyles as Styles } from './multiSelect.styles';
import { __MultiSelectOptionType, __MultiSelectProps } from './multiSelect.types';
import { FieldValues, useForm } from 'react-hook-form';

export interface MultiSelectOptionType extends __MultiSelectOptionType {}

// multi select component compatible with controller logic
const __MultiSelect: React.FC<__MultiSelectProps> = (props: __MultiSelectProps) => {
    const [filter, setFilter] = useState('');
    // get value array (not undefined or null)
    function safeArray(value?: (number | string)[]): (number | string)[] {
        if (value && Boolean(value)) return value;
        else return [];
    }

    const { register, watch } = useForm<FieldValues>({
        defaultValues: { filter: '' },
    });

    React.useEffect(() => {
        const subscription = watch((value) => setFilter(value.filter));
        return () => subscription.unsubscribe();
    }, [watch]);

    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => {
                return (
                    <div className={props.className + ' multi-select-field ' + props.name}>
                        {/* input optional title */}
                        {props.title ? <span className={Styles.title(fieldState.invalid)}>{props.title}</span> : null}
                        <input type="text" className={Styles.input + ' filter'} {...register('filter')} />
                        <div className={Styles.input}>
                            {/* input select box */}
                            <ul className={Styles.box(fieldState.invalid)}>
                                {props.options
                                    ?.filter((i) => !safeArray(field.value).includes(i.value))
                                    .map((option) => {
                                        if (option.title.includes(filter)) {
                                            return (
                                                <li // selective items
                                                    key={option.value}
                                                    onClick={() =>
                                                        field.onChange([...safeArray(field.value), option.value])
                                                    }
                                                    className={Styles.item}
                                                >
                                                    {option.title}
                                                </li>
                                            );
                                        }
                                    })}
                            </ul>
                            <FaExchangeAlt className="mx-2" size="3rem" />
                            {/* input selected result box */}
                            <ul className={Styles.box(fieldState.invalid)}>
                                {props.options
                                    ?.filter((i) => safeArray(field.value).includes(i.value))
                                    .map((option) => (
                                        <li // selective items
                                            key={option.value}
                                            onClick={() =>
                                                field.onChange([
                                                    ...safeArray(field.value).filter((i) => i !== option.value),
                                                ])
                                            }
                                            className={Styles.item}
                                        >
                                            {option.title}
                                        </li>
                                    ))}
                            </ul>
                        </div>
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

export default __MultiSelect;
