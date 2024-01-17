import { APIPath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { useQuery, UseQueryOptions } from '@tanstack/react-query';
import { DrugType } from 'types';

function get(drugID: number | string = '', params: UseQueryOptions<DrugType> = {}) {
    params['queryKey'] = ['UserData', { id: drugID }];
    params['enabled'] = !!drugID;
    params['queryFn'] = ({ queryKey }): Promise<DrugType> => {
        const [, { id }]: any = queryKey;
        return RestAPI.get<DrugType>(APIPath.drug.index(id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<DrugType>(params);
}

function getAudits(drugID: number | string = '', params: UseQueryOptions<DrugType> = {}) {
    params['queryKey'] = ['UserData', { id: drugID }];
    params['enabled'] = !!drugID;
    params['queryFn'] = ({ queryKey }): Promise<DrugType> => {
        const [, { id }]: any = queryKey;
        return RestAPI.get<DrugType>(APIPath.drug.index(id) + '/audits')
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<DrugType>(params);
}

export const DrugApi = {
    get,
    getAudits,
};
