import { APIPath, RoutePath } from 'data';
import React from 'react';
import { RestAPI } from 'scripts';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { PermissionType } from 'types';
import { FormComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

// create/edit announcement page
const PermissionFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id } = useParams<any>();
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({
        name: yup.string().required(),
        description: yup.string(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['permissionData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<PermissionType>(APIPath.permission.index(id))
                .then((response2) => {
                    const response: PermissionType = response2.data;

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

    const { handleSubmit, control, reset, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.permission.index(id), data);
            else apiCall = RestAPI.post(APIPath.permission.index(), data);

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
                alertService.error(error.response.data.message || 'Something went wrong, please try again');
            },
            onSuccess: (data: any) => {
                // Boom baby!
                if (id) {
                    alertService.success('Permission was updated successfully');
                } else {
                    alertService.success('Permission was created successfully');
                    navigate(RoutePath.permission.edit(data.data.data.id));
                }

                queryClient.setQueryData(['permissionData', { id: id }], data);
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
            title={id ? 'Update Permission: ' + getValues('name') : 'Create New Permission'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <TextInputComp className={Styles.fields} name="name" control={control} type="text" title="Name" />
            <TextInputComp
                className={Styles.fields}
                name="description"
                control={control}
                type="text"
                title="Description"
            />
        </FormComp>
    );
};

export default PermissionFormComp;
