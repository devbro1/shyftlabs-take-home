import { APIPath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { useQuery, UseQueryOptions } from '@tanstack/react-query';

function get(tableName: string, params: UseQueryOptions<any> = {}) {
    params['queryKey'] = ['TableData', { id: tableName }];
    params['enabled'] = !!tableName;
    params['queryFn'] = ({ queryKey }): Promise<any> => {
        const [, { id }]: any = queryKey;
        return RestAPI.get<any>(APIPath.database.index(id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<any>(params);
}

export const DatabaseApi = {
    get,
};
