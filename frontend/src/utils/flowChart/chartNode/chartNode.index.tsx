import React from 'react';
import { __ChartNode } from './chartNode.types';
import { __ChartNodeStyles as Styles } from './chartNode.styles';
import { Handle, Position } from 'react-flow-renderer/nocss';
import { WorkflowNodeEnum } from 'types';
import { useContext } from 'react';
import { GlobalContext } from 'context';

function __ChartNodeComp(props: __ChartNode) {
    const context = useContext(GlobalContext);

    function content() {
        let classes_source = 'w-2 h-2';
        let classes_target = 'w-2 h-2';
        if (context.reactFlowIsDraggingFrom == 'source') {
            classes_target = 'w-full h-full rounded-none opacity-25';
        } else if (context.reactFlowIsDraggingFrom == 'target') {
            classes_source = 'w-full h-full rounded-none opacity-25';
        }
        return (
            <>
                {props.type !== WorkflowNodeEnum.input ? (
                    <Handle type="target" className={classes_target} position={Position.Top} />
                ) : null}
                {props.type !== WorkflowNodeEnum.output ? (
                    <Handle type="source" className={classes_source} position={Position.Bottom} />
                ) : null}
            </>
        );
    }

    return (
        <div className={Styles.button(props.type)}>
            {props.children}
            {content()}
        </div>
    );
}

export default __ChartNodeComp;
