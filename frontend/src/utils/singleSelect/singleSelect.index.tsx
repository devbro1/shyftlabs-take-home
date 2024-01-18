import React from 'react';
import { Controller } from 'react-hook-form';
import { MultiSelectStyles as Styles } from './singleSelect.styles';
import { __MultiSelectOptionType, __MultiSelectProps } from './singleSelect.types';
import Select from 'react-select';

export interface MultiSelectOptionType extends __MultiSelectOptionType { }

// multi select component compatible with controller logic
const __MultiSelect: React.FC<__MultiSelectProps> = (props: __MultiSelectProps) =>
{
    return (
        <Controller
            control={ props.control }
            name={ props.name }
            render={ ({ field, fieldState }) =>
            {
                return (
                    <div className={ props.className + ' multi-select-field ' + props.name }>
                        { props.title ? <span className={ Styles.title(fieldState.invalid) }>{ props.title }</span> : null }
                        <Select
                            options={ props.options }
                            getOptionLabel={ (option: any) => option.title }
                            isMulti={ false }
                            className="w-full"
                            name={ props.name }
                            isClearable={true}
                            isSearchable={true}
                            onChange={ (val: any) =>
                            {
                                field.onChange(
                                    val.value
                                )
                            }
                            }
                        />
                        { fieldState.invalid && fieldState.error?.message ? (
                            <span className={ Styles.error }>{ fieldState.error?.message }</span>
                        ) : null }
                        { props.description ? <span className={ Styles.description }>{ props.description }</span> : null }
                    </div>
                );
            } }
        />
    );
};

export default __MultiSelect;
