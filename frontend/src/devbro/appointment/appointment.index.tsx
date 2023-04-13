import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import AppointmentFormComp from './form/form.index';
import AppointmentWeekComp from './list/weeks.index';
import AppointmentDayComp from './list/days.index';

const AppointmentComp: React.FC = () => {
    return (
        <Routes>
            <Route path="/days/" element={<AppointmentDayComp />} />
            <Route path="/days/:day" element={<AppointmentDayComp />} />
            <Route path="/weeks/" element={<AppointmentWeekComp />} />
            <Route path="/weeks/:week" element={<AppointmentWeekComp />} />
            <Route path="/new" element={<AppointmentFormComp />} />
            <Route path=":id" element={<AppointmentFormComp />} />
            <Route path="/" element={<AppointmentFormComp />} />
            <Route path="*" element={<Navigate to="." replace />} />
        </Routes>
    );
};

export default AppointmentComp;
