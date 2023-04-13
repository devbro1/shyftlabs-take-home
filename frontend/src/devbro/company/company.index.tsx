import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import ServiceFormComp from './form/form.index';
import ServiceListComp from './list/list.index';

const ServiceComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<ServiceFormComp />} />
            <Route path=":id" element={<ServiceFormComp />} />
            <Route path="/" element={<ServiceListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default ServiceComp;
