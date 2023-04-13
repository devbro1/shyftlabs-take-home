import { APIPath, RoutePath } from 'data';
import React from 'react';
import { RestAPI } from 'scripts';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { ServiceType } from 'types';
import { FormComp, SwitchComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';
import { alertService } from 'helperComps/Alert/AlertService';

// create/edit service page
const ServiceFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id } = useParams<any>();
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({
        name: yup.string().required(),
        active: yup.boolean().required(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['serviceData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<ServiceType>(APIPath.service.index(id))
                .then((response2) => {
                    const response: ServiceType = response2.data;

                    reset(response);
                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return {};
        }
    });

    const { reset, handleSubmit, control, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.service.index(id), data);
            else apiCall = RestAPI.post(APIPath.service.index(), data);

            return apiCall;
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
            onSuccess: (data: any) => {
                // Boom baby!
                if (id) {
                    alertService.success('Service was updated successfully');
                } else {
                    alertService.success('Service was created successfully');
                    navigate(RoutePath.service.edit(data.data.data.id));
                }

                //queryClient.invalidateQueries('roleData');
                queryClient.setQueryData(['serviceData', { id: id }], data);
            },
            // onSettled: (data, error, variables, context) => {
            //     // Error or success... doesn't matter!
            // },b
        },
    );

    // show loading if this is edit page until getting data by api call
    if (id && isLoading) {
        return (
            <div className={Styles.loading}>
                <FaSpinner size={48} className={Styles.loadingIcon} />
            </div>
        );
    }

    return (
        <FormComp
            onSubmit={handleSubmit(() => {
                mutator.mutate(getValues());
            })}
            title={id ? 'Update Service: ' + getValues('name') : 'Create New Service'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <SwitchComp name="active" control={control} title="Active" className={Styles.fields} />
            <TextInputComp className={Styles.fields} name="name" control={control} type="text" title="Name" />
        </FormComp>
    );
};

export default ServiceFormComp;
