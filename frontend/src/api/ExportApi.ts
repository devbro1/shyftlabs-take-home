import { APIPath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { useQuery, UseQueryOptions, useMutation, useQueryClient } from '@tanstack/react-query';
import { Export, SelectOption } from 'types';
import _ from 'lodash';

function get(exportId: number | string = '', params: UseQueryOptions<Export> = {}) {
    params['queryKey'] = ['Export', { id: exportId }];
    params['queryFn'] = ({ queryKey }): Promise<Export> => {
        const [, { id }]: any = queryKey;
        return RestAPI.get<Export>(APIPath.export.index(id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<Export>(params);
}

function options(params: UseQueryOptions<SelectOption[]> = {}) {
    params['queryKey'] = ['ExportsOptions', {}];
    params['queryFn'] = ({ queryKey }): Promise<SelectOption[]> => {
        const [, {}]: any = queryKey;
        return RestAPI.get<SelectOption[]>(APIPath.export.index() + '?available_options=true')
            .then((response: any) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<SelectOption[]>(params);
}

type mutatorParams = {
    setError?: Function;
    alertService?: any;
    onError?: Function;
    onSuccess?: Function;
};
function mutator(params: mutatorParams = {}) {
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
            queryClient.setQueryData(['Export', { id: data.data.data.id }], data);
        },
    };

    const post_params = _.merge(pre_params, params);
    return useMutation((data: any) => {
        return RestAPI.post(APIPath.export.index(), data);
    }, post_params);
}

export const ExportApi = {
    get,
    options,
    mutator,
};
