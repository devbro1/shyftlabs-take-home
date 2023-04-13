import { APIPath } from 'data';
import React, { useState, useEffect } from 'react';
import { RestAPI } from 'scripts';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { __DataTableColumnType } from 'helperComps/dataTable/dataTable.types';
import { ServiceAvailablityType } from 'types';
import { FormComp, SelectComp, MultiSelect, ButtonComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useSearchParams } from 'react-router-dom';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';
import { alertService } from 'helperComps/Alert/AlertService';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// create/edit service page
const ServiceAvailabityFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const [searchParams] = useSearchParams();
    const [stores, setStores] = useState<any>([]);
    const [services, setServices] = useState<any>([]);
    const [workflows, setWorkflows] = useState<any>([]);
    const validationSchema = yup.object().shape({});

    const params: any = {};

    searchParams.forEach((value, key) => {
        params[key] = value;
    });
    const queryKey = ['UserServiceAvailablityData', params];

    const columns: __DataTableColumnType[] = [
        {
            title: 'Service',
            sortable: true,
            filter: false,
            field: 'service_id',
            value: (row: ServiceAvailablityType) => {
                const f = _.find(services, { value: row.service_id });
                return f?.title;
            },
            stringContent: (row: ServiceAvailablityType) => row.service_id.toString(),
        },
        {
            title: 'Workflow',
            sortable: true,
            filter: false,
            field: 'workflow_id',
            value: (row: ServiceAvailablityType) => {
                const f = _.find(workflows, { value: row.workflow_id });
                return f?.title;
            },
            stringContent: (row: ServiceAvailablityType) => row.workflow_id.toString(),
        },
        {
            title: 'Store',
            sortable: true,
            filter: false,
            field: 'store_id',
            value: (row: ServiceAvailablityType) => {
                const f = _.find(stores, { value: row.store_id });
                return f?.title;
            },
            stringContent: (row: ServiceAvailablityType) => row.store_id.toString(),
        },
        {
            title: 'Action',
            sortable: true,
            filter: false,
            field: 'action',
            value: (row: ServiceAvailablityType) => {
                return (
                    <ButtonComp
                        onClick={() => {
                            if (confirm('Are you sure?')) {
                                RestAPI.delete(APIPath.service.availability(row.id)).then(() => {
                                    alertService.success('Service Availability was removed successfully');
                                    queryClient.invalidateQueries(queryKey);
                                });
                            }
                        }}
                    >
                        Delete
                    </ButtonComp>
                );
            },
            stringContent: (row: ServiceAvailablityType) => row.store_id.toString(),
        },
    ];

    const queryClient = useQueryClient();

    const { handleSubmit, control, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: {},
    });

    const mutator = useMutation(
        () => {
            const requestData = {
                company_id: params.company_id,
                store_id: getValues('store_id'),
                service_id: getValues('service_id'),
                workflow_id: getValues('workflow_id'),
            };

            return RestAPI.post(APIPath.service.availabilities(), requestData);
        },
        {
            onError: (error: any) => {
                // An error happened!
                _.forEach(error.response.data.errors, (value, key) => {
                    setError(key, {
                        message: RestAPI.getErrorMessage('', Object.keys(value)[0], Object.values(value)[0]),
                    });
                });
                alertService.error('Something went wrong, please try again');
            },
            onSuccess: () => {
                alertService.success('Service Availability was added successfully');
                queryClient.invalidateQueries(queryKey);
                //queryClient.setQueryData(queryKey, data);
            },
            // onSettled: (data, error, variables, context) => {
            //     // Error or success... doesn't matter!
            // },b
        },
    );

    useEffect(() => {
        RestAPI.getFormOptions(APIPath.store.index()).then((values) => {
            setStores(values);
        });

        RestAPI.getFormOptions(APIPath.workflow.index()).then((values) => {
            setWorkflows(values);
        });

        RestAPI.getFormOptions(APIPath.service.index()).then((values) => {
            setServices(values);
        });
    }, []);

    //show loading if this is edit page until getting data by api call
    const tableProps = tablePropsProvider(APIPath.service.availabilities({}), {
        urlParams: params,
        queryKey: queryKey,
    });

    return (
        <div>
            <FormComp
                onSubmit={handleSubmit(() => {
                    mutator.mutate();
                })}
                title="Manage Service Availablities"
                className={Styles.root}
                buttonTitle="Add"
            >
                <div className={Styles.row}>
                    <SelectComp
                        options={services}
                        className={Styles.fields}
                        name="service_id"
                        control={control}
                        title="Services"
                    />
                </div>
                <div className={Styles.row}>
                    <SelectComp
                        options={workflows}
                        className={Styles.fields}
                        name="workflow_id"
                        control={control}
                        title="Workflows"
                    />
                </div>
                <div className={Styles.row}>
                    <MultiSelect
                        options={stores}
                        className={Styles.fields}
                        name="store_id"
                        control={control}
                        title="Stores"
                    />
                </div>
            </FormComp>
            <DataTableComp {...tableProps} columns={columns} />
        </div>
    );
};

export default ServiceAvailabityFormComp;
