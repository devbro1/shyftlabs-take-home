import _ from 'lodash';
import { __RestAPI as RestAPI } from 'scripts/api';
import { useEffect, useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { useQueryClient } from '@tanstack/react-query';

function tablePropsProvider(url: string, params: any = {}) {
    const queryClient = useQueryClient();
    const [page, setPage] = useState(1);
    const [pageCount, setPageCount] = useState(1);
    const [pageSize, setPageSize] = useState(20);
    const [dataTotal, setDataTotal] = useState(0);
    const [dataFrom, setDataFrom] = useState(0);
    const [dataTo, setDataTo] = useState(0);
    const [orderByField, setOrderByField] = useState('');
    const [orderByDirection, setOrderByDirection] = useState('');
    const [filters, setFilters] = useState({});

    params.urlParams = params.urlParams || {};
    params.queryKey = params.queryKey || url;

    const searchParams = new URLSearchParams();
    searchParams.append('page[number]', page.toString());
    searchParams.append('page[per_page]', pageSize.toString());
    searchParams.append('sort', orderByDirection + orderByField);
    _.forEach(filters, (val, key) => {
        if (typeof val === 'string') {
            searchParams.append('filter[' + key + ']', val);
        } else {
            _.forEach(val, (val2: string, key2) => {
                searchParams.append('filter[' + key + '.' + key2 + ']', val2);
            });
        }
    });

    _.forEach(params.urlParams, (val, key) => {
        if (val) {
            searchParams.append(key, val);
        }
    });

    useEffect(() => {
        queryClient.invalidateQueries({
            queryKey: [params.queryKey],
            exact: false,
            type: 'all',
            refetchType: 'active',
        });
    }, []);

    const useQueryOptionsBase = {
        queryKey: [params.queryKey, searchParams.toString()],
        queryFn: (queryKey: any) => {
            return RestAPI.get(url + '?' + queryKey.queryKey[1])
                .then((response: any) => {
                    return response.data;
                })
                .catch((ex) => {
                    throw ex;
                });
        },
        staleTime: 60 * 1000, // 1 minute
    };
    const useQueryOptions = { ...(params.useQueryOptions || {}), ...useQueryOptionsBase };
    const { data, isLoading } = params.useQuery || useQuery(useQueryOptions);

    useEffect(() => {
        if (data) {
            setPageCount(data.last_page);
            setPageSize(data.per_page);
            setDataTotal(data.total);
            setDataFrom(data.from);
            setDataTo(data.to);
        }
    }, [data]);

    function onTableChange(params: any) {
        if (params.pageSize) {
            setPageSize(params.pageSize);
        }

        if (params.page) {
            setPage(params.page);
        }

        if (params.orderby) {
            setOrderByField(params.orderby);
            setOrderByDirection(params.direction);
        }

        if (params.filters) {
            setFilters(params.filters);
        }
    }
    const rc = {
        data: data?.data || [],
        onChange: onTableChange,
        isLoading: isLoading,
        pageSize: pageSize,
        page: page,
        pageCount: pageCount,
        from: dataFrom,
        to: dataTo,
        total: dataTotal,
        sort: orderByField,
        sortDirection: orderByDirection,
    };

    return rc;
}

export default tablePropsProvider;
