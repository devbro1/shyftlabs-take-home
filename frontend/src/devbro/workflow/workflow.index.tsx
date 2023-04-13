import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import WorkflowFormComp from './form/form.index';
import ActionFormComp from './form/node.form';
import WorkflowListComp from './list/list.index';

const WorkflowComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<WorkflowFormComp />} />
            <Route path=":id" element={<WorkflowFormComp />} />
            <Route path="/" element={<WorkflowListComp />} />
            <Route path=":id/nodes/:action_id" element={<ActionFormComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default WorkflowComp;
