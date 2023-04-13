import React from 'react';
import { ReactFlowProvider } from 'react-flow-renderer/nocss';
import { __FlowChartProps } from './flowChart.types';
import __FlowComp from './flow/flow.index';
import { Controller } from 'react-hook-form';
import __Sidebar from './sidebar/sidebar.index';
import { __flowChartStyles as Styles } from './flowChart.styles';

// flow chart input component
function __FlowChartComp(props: __FlowChartProps) {
    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => (
                <div className={props.className}>
                    {/* input optional title */}
                    {props.title ? <span className={Styles.title(fieldState.invalid)}>{props.title}</span> : null}
                    <div className={Styles.root(fieldState.invalid)}>
                        <ReactFlowProvider>
                            <__FlowComp
                                value={field.value}
                                onChange={field.onChange}
                                onNameChanged={props.onNameChanged}
                                nodeContentRenderer={props.nodeContentRenderer}
                                nodeTypes={props.nodeTypes}
                            />
                            <__Sidebar />
                        </ReactFlowProvider>
                    </div>
                    {/* input error message */}
                    {fieldState.invalid && fieldState.error?.message ? (
                        <span className={Styles.error}>{fieldState.error?.message}</span>
                    ) : null}
                    {/* input optional description */}
                    {props.description ? <span className={Styles.description}>{props.description}</span> : null}
                </div>
            )}
        />
    );
}

export default __FlowChartComp;
