import { __FlowChartProps } from 'utils/flowChart/flowChart.types';
import { __TextEditorProps } from 'utils/textEditor/textEditor.types';
import { __TextInputProps } from '../textInput/textInput.types';

export type __InputProps = SimpleInputProps | TextEditorProps | FlowChartProps;

interface SimpleInputProps extends __TextInputProps {
    inputType: __InputTypes.simple;
}
interface TextEditorProps extends __TextEditorProps {
    inputType: __InputTypes.textEditor;
}
interface FlowChartProps extends __FlowChartProps {
    inputType: __InputTypes.flowchart;
}
export enum __InputTypes {
    simple = 'simple',
    date = 'date',
    file = 'file',
    textEditor = 'textEditor',
    multiSelect = 'multiSelect',
    flowchart = 'flowchart',
}
