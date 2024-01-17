import { APIPath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { useQuery, UseQueryOptions, useMutation } from '@tanstack/react-query';
import { SelectOption } from 'types';
import _ from 'lodash';
import { mutatorParams } from './types';

function get(params: UseQueryOptions<any[]> = {}) {
    params['queryKey'] = ['Processors', {}];
    params['queryFn'] = ({ queryKey }): Promise<any[]> => {
        const [, {}]: any = queryKey;
        return RestAPI.get<any[]>(APIPath.Processor.index())
            .then((response: any) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<SelectOption[]>(params);
}

function options(params: UseQueryOptions<SelectOption[]> = {}) {
    params['queryKey'] = ['ProcessorOptions', {}];
    params['queryFn'] = ({ queryKey }): Promise<SelectOption[]> => {
        const [, {}]: any = queryKey;
        return RestAPI.get<SelectOption[]>(APIPath.Processor.index())
            .then((response: any) => {
                const rc: SelectOption[] = [];
                _.forEach(response.data, (value) => {
                    rc.push({ value: value.class, title: value.name });
                });
                return rc;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<SelectOption[]>(params);
}

function ChangeRequestOptions(params: UseQueryOptions<SelectOption[]> = {}) {
    params['queryKey'] = ['ChangeRequestProcessorOptions', {}];
    params['queryFn'] = ({ queryKey }): Promise<SelectOption[]> => {
        const [, {}]: any = queryKey;
        return RestAPI.get<SelectOption[]>(APIPath.Processor.index())
            .then((response: any) => {
                const rc: SelectOption[] = [];
                _.forEach(response.data, (value) => {
                    if (value.class.startsWith('ChangeRequestProcessors')) {
                        rc.push({ value: value.class, title: value.name });
                    }
                });
                return rc;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<SelectOption[]>(params);
}

function DrugOptions(params: UseQueryOptions<SelectOption[]> = {}) {
    params['queryKey'] = ['DrugProcessorOptions', {}];
    params['queryFn'] = ({ queryKey }): Promise<SelectOption[]> => {
        const [, {}]: any = queryKey;
        return RestAPI.get<SelectOption[]>(APIPath.Processor.index())
            .then((response: any) => {
                const rc: SelectOption[] = [];
                _.forEach(response.data, (value) => {
                    if (value.class.startsWith('DrugProcessors')) {
                        rc.push({ value: value.class, title: value.name });
                    }
                });
                return rc;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<SelectOption[]>(params);
}

function mutator(params: mutatorParams = {}) {
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
        },
    };

    const post_params = _.merge(pre_params, params);
    return useMutation((data: any) => {
        return RestAPI.post(APIPath.Processor.index(), data);
    }, post_params);
}

export const ProcessorApi = {
    get,
    options,
    ChangeRequestOptions,
    DrugOptions,
    mutator,
};
