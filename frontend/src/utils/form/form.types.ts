import { ReactElement, ReactNode } from 'react';
import { FieldValues, UseFormHandleSubmit } from 'react-hook-form';

export interface __FormProps {
    title?: string | ReactElement;
    buttonTitle?: string;
    onSubmit?: () => void | Promise<any> | Function; // condition of submit button
    controllerSubmit?: UseFormHandleSubmit<FieldValues>;
    children?: ReactNode;
    className?: string;
    isLoading?: boolean;
}
