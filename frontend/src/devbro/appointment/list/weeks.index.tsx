import { RoutePath } from 'data';
import React from 'react';
//import { useParams } from 'react-router-dom';
import { Link } from 'react-router-dom';
//import { DefaultStyle as Styles } from 'default.styles';
import moment from 'moment';

const AppointmentWeekComp: React.FC = () => {
    //  const { week } = useParams<any>();
    const t = moment();
    const total_days = 21;
    const days = [];
    t.startOf('week').subtract(7, 'd');

    for (let i = 0; i < total_days; i++) {
        days.push(
            <div>
                <Link to={RoutePath.appointment.days(t.format('YYYY-MM-DD'))}>{t.format('ddd MMM-Do-YYYY')}</Link>
            </div>,
        );
        t.add(1, 'd');
    }

    return (
        <div>
            <div>weekly appointments:</div>
            <div>{days}</div>
        </div>
    );
};

export default AppointmentWeekComp;
