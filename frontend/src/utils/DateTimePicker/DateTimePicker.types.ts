import { Control, FieldValues } from 'react-hook-form';

export interface __DateTimePickerProps {
    control: Control<FieldValues, object>;
    name: string;
    title?: string;
    description?: string;
    type?: 'text' | 'number' | 'password';
    placeholder?: string;
    className?: string;

    beginningOfWeek?: number;
    outputFormat?: string;
    topFormat?: string;
    generateList?: any;
    timePortions?: string[];
    showTime?: boolean;
    week_titles?: string[];
    textNow?: string;
    textToday?: string;
    fieldIcon?: any;

    icon_last_year?: any;
    icon_last_month?: any;

    icon_next_month?: any;
    icon_next_year?: any;
}
