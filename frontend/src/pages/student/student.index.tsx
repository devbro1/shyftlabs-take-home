import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import StudentFormComp from './form/form.index';
import StudentListComp from './list/list.index';

const StudentComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<StudentFormComp />} />
            <Route path=":id" element={<StudentFormComp />} />
            <Route path="/" element={<StudentListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default StudentComp;
