import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import LeadFormComp from './form/form.index';
import LeadListComp from './list/list.index';
import LeadActions from './form/actions/index';

const ServiceComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<LeadFormComp />} />
            <Route path=":id" element={<LeadFormComp />} />
            <Route path=":id/actions/*" element={<LeadActions />} />
            <Route path="/" element={<LeadListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default ServiceComp;
