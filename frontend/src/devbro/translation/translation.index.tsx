import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import TranslationFormComp from './form/form.index';
import TranslationListComp from './list/list.index';

const TranslationComp: React.FC = () => {
    return (
        <Routes>
            <Route path="new" element={<TranslationFormComp />} />
            <Route path=":id" element={<TranslationFormComp />} />
            <Route path="/" element={<TranslationListComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default TranslationComp;
