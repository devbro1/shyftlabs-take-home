import { APIPath, RoutePath } from 'data';
import React, { useEffect, useState } from 'react';
import { __RestAPI as RestAPI } from 'scripts/api';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { RoleType } from 'types';
import { FormComp, TextInputComp } from 'utils';
import { MultiSelect } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

// create/edit announcement page
const RoleFormComp: React.FC = () => {
    const id = useParams<any>().id;
    const navigate = useNavigate();
    const [permissions, setPermissions] = useState([]);
    const validationSchema = yup.object().shape({
        name: yup.string().required(),
        description: yup.string(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['roleData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<RoleType>(APIPath.role.index(id))
                .then((response2) => {
                    const response: RoleType = response2.data;
                    const selected_perms: any[] = [];

                    response2.data.permissions.map((perm) => {
                        selected_perms.push(perm.id);
                    });

                    response.permissions = selected_perms;

                    reset(response);
                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return { name: '', description: '' };
        }
    });

    const { handleSubmit, control, reset, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.role.index(id), data);
            else apiCall = RestAPI.post(APIPath.role.index(), data);

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
                    alertService.success('Role was updated successfully');
                } else {
                    alertService.success('Role was created successfully');
                    navigate(RoutePath.role.edit(data.data.data.id));
                }

                //queryClient.invalidateQueries('roleData');
                queryClient.setQueryData(['roleData', { id: id }], data);
            },
            // onSettled: (data, error, variables, context) => {
            //     // Error or success... doesn't matter!
            // },b
        },
    );

    useEffect(() => {
        RestAPI.getFormOptions(APIPath.permission.index()).then((values) => {
            setPermissions(values);
        });
    }, []);

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
            title={id ? 'Update Role: ' + getValues('name') : 'Create New Role'}
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

            <div className={Styles.row}>
                <MultiSelect
                    options={permissions}
                    className={Styles.fields}
                    name="permissions"
                    control={control}
                    title="User Permissions"
                />
            </div>
        </FormComp>
    );
};

export default RoleFormComp;
