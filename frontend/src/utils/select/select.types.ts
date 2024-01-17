import { Control, FieldValues } from 'react-hook-form';

export interface __SelectProps {
    control: Control<FieldValues, object>;
    name: string;
    title?: string;
    description?: string;
    type?: 'text' | 'number' | 'password';
    placeholder?: string;
    unSelectable?: boolean;
    className?: string;
    inputClassName?: string;
    options?: __SelectOptionType[];
    disabled?: boolean;
}

export interface __SelectOptionType {
    title: string;
    value: string | number;
}
