import { APIPath } from 'data';
import { RestAPI } from 'scripts';
import React from 'react';
import { useParams } from 'react-router-dom';
import moment from 'moment';
import { useQuery, useQueryClient } from '@tanstack/react-query';
//import _ from 'lodash';
import { AppointmentType } from 'types/models';

const AppointmentDayComp: React.FC = () => {
    const { day } = useParams<any>();
    //const t = moment(day);

    const queryClient = useQueryClient();

    const { data: appointments } = useQuery(
        ['appointmentsList', { day: day }] as const,
        ({ queryKey }) => {
            const [, { day }] = queryKey;
            return RestAPI.get<AppointmentType[]>(
                APIPath.appointment.index() + '?dt_start=' + day + ' 000:00:00,' + day + ' 23:59:59',
            )
                .then((response2: any) => {
                    const response: AppointmentType[] = response2.data.data;

                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        },
        { placeholderData: [] },
    );

    const appts = [];
    if (appointments !== undefined) {
        for (const a of appointments) {
            const start = moment(a.dt_start);
            const end = moment(a.dt_end);
            const deleteAppointment = () => {
                RestAPI.delete(APIPath.appointment.index(a.id)).then(() => {
                    queryClient.invalidateQueries(['appointmentsList', { day: day }]);
                });
            };

            appts.push(
                <div>
                    Appointment: {start.format('HH:mm')} = {end.format('HH:mm')}
                    <div onClick={deleteAppointment}>DELETE</div>
                </div>,
            );
        }
    }

    return (
        <div>
            <div>Appoints for a single day:</div>
            {appts}
        </div>
    );
};

export default AppointmentDayComp;
