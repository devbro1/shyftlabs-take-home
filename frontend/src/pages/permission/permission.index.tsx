import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import PermissionFormComp from './form/form.index';
import PermissionListComp from './list/list.index';

const PermissionComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<PermissionFormComp />} />
            <Route path=":id" element={<PermissionFormComp />} />
            <Route path="/" element={<PermissionListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default PermissionComp;
