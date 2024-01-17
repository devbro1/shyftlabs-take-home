import { APIPath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { useQuery, UseQueryOptions, useMutation, useQueryClient } from '@tanstack/react-query';
import { ChangeReuqest } from 'types';
import _ from 'lodash';
import { mutatorParams } from './types';

function get(changeRequestId: number | string = '', params: UseQueryOptions<ChangeReuqest> = {}) {
    params['queryKey'] = ['ChangeRequest', { id: changeRequestId }];
    params['queryFn'] = ({ queryKey }): Promise<ChangeReuqest> => {
        const [, { id }]: any = queryKey;
        return RestAPI.get<ChangeReuqest>(APIPath.changeRequest.index(id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<ChangeReuqest>(params);
}

function getByDrugId(DrugId: number, params: UseQueryOptions<ChangeReuqest[]> = {}) {
    params['queryKey'] = ['ChangeRequestByDrugId', { id: DrugId }];
    params['queryFn'] = (): Promise<ChangeReuqest[]> => {
        return RestAPI.get<ChangeReuqest[]>(
            APIPath.changeRequest.index() + '?&filter[status]=PENDING&filter[drug_id]=' + DrugId,
        )
            .then((response: any) => {
                return response.data.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<ChangeReuqest[]>(params);
}

function next(changeRequestId: number | string = '') {
    return RestAPI.get<ChangeReuqest>(APIPath.changeRequest.index(changeRequestId) + '/next');
}

function mutator(id: number | string, params: mutatorParams = {}) {
    const queryClient = useQueryClient();
    const pre_params = {
        onError: (error: any) => {
            // An error happened!
            if (params.setError) {
                _.forEach(error.response.data.errors, (value, key) => {
                    params.setError &&
                        params.setError(key, {
                            message: RestAPI.getErrorMessage('', Object.keys(value)[0], Object.values(value)[0]),
                        });
                });
            }
            if (params.alertService) {
                params.alertService.error(error.response.data.message || 'Something went wrong, please try again');
            }
        },

        onSuccess: (data: any) => {
            if (params.alertService) {
                params.alertService.success(data.data.message, { timeout: '5000' });
            }
            queryClient.setQueryData(['ChangeRequest', { id: id }], data.data.data);
        },
    };

    const post_params = _.merge(pre_params, params);
    return useMutation((data: any) => {
        let apiCall;
        if (id) apiCall = RestAPI.put(APIPath.changeRequest.index(id), data);
        else apiCall = RestAPI.post(APIPath.changeRequest.index(), data);

        return apiCall;
    }, post_params);
}

function clearCache(queryClient: any) {
    queryClient.invalidateQueries({
        queryKey: ['ChangeRequest'],
        exact: false,
        type: 'all',
        refetchType: 'active',
    });
}

export const ChangeRequestApi = {
    get,
    next,
    mutator,
    clearCache,
    getByDrugId,
};
