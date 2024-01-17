import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import ResultFormComp from './form/form.index';
import ResultListComp from './list/list.index';

const ResultComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<ResultFormComp />} />
            <Route path=":id" element={<ResultFormComp />} />
            <Route path="/" element={<ResultListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default ResultComp;
