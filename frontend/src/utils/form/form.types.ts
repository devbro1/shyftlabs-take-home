import { ReactNode } from 'react';
import { FieldValues, UseFormHandleSubmit } from 'react-hook-form';

export interface __FormProps {
    title?: string;
    buttonTitle?: string;
    onSubmit?: () => void | Promise<any> | Function; // condition of submit button
    controllerSubmit?: UseFormHandleSubmit<FieldValues>;
    children?: ReactNode;
    className?: string;
    isLoading?: boolean;
}
