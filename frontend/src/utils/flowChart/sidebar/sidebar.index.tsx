import React from 'react';
import { WorkflowNodeEnum } from 'types';
import { __sidebarStyles as Styles } from './sidebar.styles';

function __Sidebar() {
    // on dragging source elements
    const onDragStart = (event: React.DragEvent<HTMLDivElement>, nodeType: string) => {
        event.dataTransfer.setData('application/reactflow', nodeType);
        event.dataTransfer.effectAllowed = 'move';
    };

    return (
        <aside className={Styles.root}>
            <div className={Styles.description}>You can drag these nodes to the pane on the right.</div>
            <div
                className={Styles.element(WorkflowNodeEnum.input)}
                onDragStart={(event) => onDragStart(event, WorkflowNodeEnum.input)}
                draggable
            >
                Start Node
            </div>
            <div
                className={Styles.element(WorkflowNodeEnum.default)}
                onDragStart={(event) => onDragStart(event, WorkflowNodeEnum.default)}
                draggable
            >
                middle Node
            </div>
            <div
                className={Styles.element(WorkflowNodeEnum.output)}
                onDragStart={(event) => onDragStart(event, WorkflowNodeEnum.output)}
                draggable
            >
                End Node
            </div>
        </aside>
    );
}

export default __Sidebar;
