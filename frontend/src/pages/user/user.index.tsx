import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import UserFormComp from './form/form.index';
import UserListComp from './list/list.index';

const UserComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<UserFormComp />} />
            <Route path=":id" element={<UserFormComp />} />
            <Route path="/" element={<UserListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default UserComp;
