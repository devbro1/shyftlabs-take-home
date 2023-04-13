import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import RoleFormComp from './form/form.index';
import RoleListComp from './list/list.index';

const RoleComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<RoleFormComp />} />
            <Route path=":id" element={<RoleFormComp />} />
            <Route path="/" element={<RoleListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default RoleComp;
