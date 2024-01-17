import { APIPath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { useQuery, UseQueryOptions } from '@tanstack/react-query';
import { UserType } from 'types';

function get(userId: number, params: UseQueryOptions<UserType> = {}) {
    params['queryKey'] = ['UserData', { id: userId }];
    params['enabled'] = !!userId;
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

export const UsersApi = {
    get,
};
