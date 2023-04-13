import { Control, FieldValues } from 'react-hook-form';

export interface __TextEditorProps {
    control: Control<FieldValues, object>;
    name: string;
    title?: string;
    description?: string;
    placeholder?: string;
    className?: string;
}
