import { APIPath } from 'data';
import { RestAPI } from 'scripts';
import { useQuery, UseQueryOptions } from '@tanstack/react-query';
import { UserType } from 'types';
import { AppointmentType } from 'types/models';

function get(userId: number, params: UseQueryOptions<UserType> = {}) {
    params['queryKey'] = ['UserData', { id: userId }];
    params['queryFn'] = ({ queryKey }): Promise<UserType> => {
        const [, { id }]: any = queryKey;
        return RestAPI.get<UserType>(APIPath.user.index(id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<UserType>(params);
}

function getAppointments(userId: number, params: UseQueryOptions<AppointmentType[]> = {}, filter: object = {}) {
    params['queryKey'] = ['userAppointmentData', { user_id: userId, filter: filter }];
    params['queryFn'] = ({ queryKey }): Promise<AppointmentType[]> => {
        const [, { user_id, filter }]: any = queryKey;
        const query = new URLSearchParams(filter).toString();
        return RestAPI.get<AppointmentType[]>(APIPath.user.appointments(user_id) + '?' + query)
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    params['select'] = (data: any) => {
        return data.data;
    };

    if (!userId) {
        params['enabled'] = false;
    }
    return useQuery(params);
}

export const UsersApi = {
    get,
    getAppointments,
};
