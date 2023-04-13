import { Control, FieldValues } from 'react-hook-form';

export interface __FileInputProps {
    control: Control<FieldValues, object>;
    name: string;
    title?: string;
    description?: string;
    placeholder?: string;
    className?: string;
    setError: Function;
    clearErrors: Function;
}
