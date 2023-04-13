import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import ActionFormComp from './form/form.index';
import ActionListComp from './list/list.index';

const ActionComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<ActionFormComp />} />
            <Route path=":id" element={<ActionFormComp />} />
            <Route path="/" element={<ActionListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default ActionComp;
