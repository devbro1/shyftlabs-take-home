import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import CourseFormComp from './form/form.index';
import CourseListComp from './list/list.index';

const CourseComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<CourseFormComp />} />
            <Route path=":id" element={<CourseFormComp />} />
            <Route path="/" element={<CourseListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default CourseComp;
