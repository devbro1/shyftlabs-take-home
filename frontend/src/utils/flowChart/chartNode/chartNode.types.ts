import React from 'react';

export interface __ChartNode {
    id: string;
    data: { label: string };
    type: string;
    selected: boolean;
    sourcePosition: string;
    targetPosition: string;
    nodeContentRenderer: any;
    onNameEditingFinished: (id: string) => void;
    children?: any;
}
