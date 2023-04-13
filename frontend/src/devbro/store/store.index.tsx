import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import StoreFormComp from './form/form.index';
import StoreListComp from './list/list.index';

const StoreComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<StoreFormComp />} />
            <Route path=":id" element={<StoreFormComp />} />
            <Route path="/" element={<StoreListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default StoreComp;
