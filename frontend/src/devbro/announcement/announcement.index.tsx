import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import AnnouncementFormComp from './form/form.index';
import AnnouncementListComp from './list/list.index';

const AnnouncementComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<AnnouncementFormComp />} />
            <Route path=":id" element={<AnnouncementFormComp />} />
            <Route path="/" element={<AnnouncementListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default AnnouncementComp;
