import { Control, FieldValues } from 'react-hook-form';

export interface __FlowChartProps {
    control: Control<FieldValues, object>;
    name: string;
    title?: string;
    description?: string;
    className?: string;
    nodeContentRenderer?: any;
    onNameChanged: Function;
    nodeTypes: any;
}
