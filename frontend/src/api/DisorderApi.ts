import { APIPath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { Disorder, SelectOption } from 'types';
import { useQuery, UseQueryOptions, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';
import { mutatorParams } from './types';

function get(disorderID: number | string = '', params: UseQueryOptions<Disorder> = {}) {
    params['queryKey'] = ['Disorder', { id: disorderID }];
    params['queryFn'] = ({ queryKey }): Promise<Disorder> => {
        const [, { id }]: any = queryKey;
        if (!id) {
            return new Promise((resolve) => {
                const rc: Disorder = { id: 0, name: '', category: '', used_for_code: '' };
                resolve(rc);
            });
        }
        return RestAPI.get<Disorder>(APIPath.disorder.index(id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<Disorder>(params);
}

function options(params: UseQueryOptions<SelectOption[]> = {}) {
    params['queryKey'] = ['DisordersOptions', {}];
    params['queryFn'] = ({ queryKey }): Promise<SelectOption[]> => {
        const [, {}]: any = queryKey;
        return RestAPI.get<SelectOption[]>(APIPath.disorder.index() + '?page[per_page]=1000&sort=name')
            .then((response: any) => {
                const rc: SelectOption[] = [];
                _.forEach(response.data.data, (value) => {
                    rc.push({ value: value.id, title: value.name });
                });
                return rc;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<SelectOption[]>(params);
}

function getAll(params: UseQueryOptions<Disorder[]> = {}) {
    params['queryKey'] = ['AllDisorders', {}];
    params['queryFn'] = ({ queryKey }): Promise<Disorder[]> => {
        const [, {}]: any = queryKey;
        return RestAPI.get<Disorder[]>(APIPath.disorder.index() + '?page[per_page]=1000&sort=name')
            .then((response: any) => {
                return response.data.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<Disorder[]>(params);
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
            queryClient.setQueryData(['Disorder', { id: id }], data);
        },
    };

    const post_params = _.merge(pre_params, params);
    return useMutation((data: any) => {
        let apiCall;
        if (id) apiCall = RestAPI.put(APIPath.disorder.index(id), data);
        else apiCall = RestAPI.post(APIPath.disorder.index(), data);

        return apiCall;
    }, post_params);
}

export const DisorderApi = {
    get,
    mutator,
    options,
    getAll,
};
