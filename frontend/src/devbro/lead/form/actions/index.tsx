import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import ConfirmMessageFormComp from './confirmMessageAction.form';
import QuickConfirmMessageFormComp from './QuickconfirmMessageAction.form';
import TestFormComp from './testAction.form';
import SetDateFormComp from './setDateAction.form';
import BookAppointmentFormComp from './bookAppointmentAction.form';
import FillInvoiceFormComp from './FillInvoiceAction.form';

const LeadActions: React.FC = () => {
    return (
        <Routes>
            <Route path="book-appointment/:action_id" element={<BookAppointmentFormComp />} />
            <Route path="confirm-message/:action_id" element={<ConfirmMessageFormComp />} />
            <Route path="quick-confirm-message/:action_id" element={<QuickConfirmMessageFormComp />} />
            <Route path="set-date/:action_id" element={<SetDateFormComp />} />
            <Route path="fill-invoice/:action_id" element={<FillInvoiceFormComp />} />
            <Route path="test/:action_id" element={<TestFormComp />} />
            <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
    );
};

export default LeadActions;
