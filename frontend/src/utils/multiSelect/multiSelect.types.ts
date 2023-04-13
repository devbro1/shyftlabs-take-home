import { Control, FieldValues } from 'react-hook-form';

export interface __MultiSelectProps {
    control: Control<FieldValues, object>;
    name: string;
    title?: string;
    description?: string;
    type?: 'text' | 'number' | 'password';
    placeholder?: string;
    className?: string;
    options?: __MultiSelectOptionType[];
}

export interface __MultiSelectOptionType {
    title: string;
    value: string | number;
}
