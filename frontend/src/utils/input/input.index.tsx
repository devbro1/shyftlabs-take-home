import React from 'react';
import { __InputProps, __InputTypes } from './input.types';
import __TextInputComp from '../textInput/textInput.index';
import __TextEditorComp from 'utils/textEditor/textEditor.index';

// general input component of utilities to map to target utility
const __InputComp: React.FC<__InputProps> = (props: __InputProps) => {
    switch (props.inputType) {
        case __InputTypes.simple:
            return <__TextInputComp {...props} />;
        case __InputTypes.textEditor:
            return <__TextEditorComp {...props} />;
        default:
            return <__TextInputComp {...props} />;
    }
};

export default __InputComp;
