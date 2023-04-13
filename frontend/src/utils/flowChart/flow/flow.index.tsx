import React, { useEffect, useRef, useCallback, useState, useMemo } from 'react';
import ReactFlow, {
    Background,
    Controls,
    useNodesState,
    useEdgesState,
    addEdge,
    ReactFlowInstance,
    // OnLoadParams,
    // removeElements,
} from 'react-flow-renderer/nocss';
import 'react-flow-renderer/dist/style.css';
import 'react-flow-renderer/dist/theme-default.css';

import { __FlowProps } from './flow.types';
import { __FlowStyles as Styles } from './flow.styles';
import PropTypes from 'prop-types';
import { useContext } from 'react';
import { GlobalContext } from 'context';
import { AppContextActionKeyEnum } from 'types';

let id = 0;
const getId = () => `dndnode_${id++}`;

// flow chart main functionalities component
function __FlowComp(props: __FlowProps) {
    const context = useContext(GlobalContext);
    const reactFlowWrapper = useRef<HTMLDivElement>(null);
    const onDragOver = (event: React.DragEvent<HTMLDivElement>) => {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
    };

    const [nodes, setNodes, onNodesChange] = useNodesState(props.value.nodes);
    const [edges, setEdges, onEdgesChange] = useEdgesState(props.value.edges);
    const [reactFlowInstance, setReactFlowInstance] = useState<ReactFlowInstance>();
    const nodeTypes = useMemo(() => {
        return props.nodeTypes;
    }, []);

    useEffect(() => {
        props.onChange({ nodes: nodes, edges: edges });
    }, [nodes, edges]);

    useEffect(() => {
        setNodes(props.value.nodes);
        setEdges(props.value.edges);
    }, [props.value]);

    const onDrop = useCallback(
        (event) => {
            event.preventDefault();

            if (!reactFlowWrapper.current || !reactFlowInstance) {
                return;
            }

            const reactFlowBounds = reactFlowWrapper.current.getBoundingClientRect();
            const type = event.dataTransfer.getData('application/reactflow');

            // check if the dropped element is valid
            if (typeof type === 'undefined' || !type) {
                return;
            }

            const position = reactFlowInstance.project({
                x: event.clientX - reactFlowBounds.left,
                y: event.clientY - reactFlowBounds.top,
            });

            const newNode = {
                id: getId(),
                type,
                position,
                data: { label: `${type} node`, onNameChanged: props.onNameChanged },
            };

            setNodes((nds) => nds.concat(newNode));
        },
        [reactFlowInstance],
    );

    const onConnectStart = useCallback((events, params) => {
        context.update({ key: AppContextActionKeyEnum.reactFlowIsDraggingFrom, value: params.handleType });
    }, []);

    const onConnectEnd = useCallback(() => {
        context.update({ key: AppContextActionKeyEnum.reactFlowIsDraggingFrom, value: false });
    }, []);

    const onConnect = useCallback((params) => setEdges((eds) => addEdge(params, eds)), []);

    return (
        <ReactFlow
            className={Styles.root}
            ref={reactFlowWrapper}
            edges={edges}
            nodes={nodes}
            onNodesChange={onNodesChange}
            onEdgesChange={onEdgesChange}
            onInit={setReactFlowInstance}
            onDrop={onDrop}
            onDragOver={onDragOver}
            onConnect={onConnect}
            onConnectStart={onConnectStart}
            onConnectEnd={onConnectEnd}
            nodeTypes={nodeTypes}
            snapToGrid={true}
            snapGrid={[10, 10]}
        >
            <Controls />
            <Background color="#aaa" gap={10} />
        </ReactFlow>
    );
}

__FlowComp.propTypes = {
    nodeContentRenderer: PropTypes.any,
};

__FlowComp.defaultProps = {
    nodeContentRenderer: () => {
        return;
    },
};
export default __FlowComp;
