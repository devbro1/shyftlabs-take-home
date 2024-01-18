import { APIPath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { SelectOption } from 'types';
import { useQuery, UseQueryOptions } from '@tanstack/react-query';
import _ from 'lodash';

function options(params: UseQueryOptions<SelectOption[]> = {}) {
    params['queryKey'] = ['CoursesOptions', {}];
    params['queryFn'] = ({ queryKey }): Promise<SelectOption[]> => {
        const [, {}]: any = queryKey;
        return RestAPI.get<SelectOption[]>(APIPath.course.index() + '?page[per_page]=1000&sort=name')
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

export const CoursesApi = {
    options,
};