import React from 'react';
import { Node, Edge } from 'react-flow-renderer/nocss';

export interface __FlowProps {
    value: { nodes: Node[]; edges: Edge[] };
    onChange: (e: { nodes: Node[]; edges: Edge[] }) => void;
    nodeContentRenderer: React.FC;
    onNameChanged: Function;
    nodeTypes: any;
}
