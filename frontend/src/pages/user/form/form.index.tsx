import { APIPath, RoutePath } from 'data';
import React, { useEffect, useState } from 'react';
import { CookiesInterface } from 'scripts';
import { __RestAPI as RestAPI } from 'scripts/api';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { UserType } from 'types';
import { AppContextActionKeyEnum, AuthStatusEnum } from 'types';
import { useContext } from 'react';
import { GlobalContext } from 'context';
import { FormComp, MultiSelect, SelectComp, SwitchComp, TextInputComp, ButtonComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

// create/edit user page
const UserFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const id: number = parseInt(useParams<any>().id || '0');

    const navigate = useNavigate();
    const [countries, setCountries] = useState<any[]>([]);
    const [countryOptions, setCountryOptions] = useState<any[]>([]);
    const provinceOptions: any[] = [];
    const [permissions, setPermissions] = useState([]);
    const [roles, setRoles] = useState([]);
    const context = useContext(GlobalContext);

    const validationSchema = yup.object().shape({
        active: yup.boolean().required(),
        username: yup.string().required(),
        full_name: yup.string().required(),
        email: yup.string().email().required(),
        address: yup.string().required(),
        city: yup.string().required(),
        postal_code: yup.string().required(),
        province_code: yup.string().required(),
        country_code: yup.string().required(),
        phone1: yup.string().required(),
        phone2: yup.string().nullable(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery<UserType, Error>(['userData', { id: id }], (params: any) => {
        const [, { id }] = params.queryKey;

        if (id) {
            return RestAPI.get<UserType>(APIPath.user.index(id))
                .then((response2) => {
                    const response: UserType = response2.data;
                    const selected_perms: any[] = [];
                    const selected_roles: any[] = [];

                    response.permissions.map((perm) => {
                        selected_perms.push(perm.id);
                    });
                    response.available_permissions = response.permissions;
                    response.permissions = selected_perms;

                    response.roles.map((role) => {
                        selected_roles.push(role.id);
                    });

                    response.available_roles = response.roles;
                    response.roles = selected_roles;

                    reset(response);
                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return {} as UserType;
        }
    });

    const { handleSubmit, control, reset, getValues, setError, watch } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

    const country_of_provinces = watch('country_code');
    if (countries) {
        const country = countries?.find((i) => i.code === country_of_provinces);
        country?.provinces.map((prov: any) => provinceOptions.push({ title: prov.name, value: prov.code }));
    }

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.user.index(id), data);
            else apiCall = RestAPI.post(APIPath.user.index(), data);

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
                    alertService.success('User was updated successfully');
                } else {
                    alertService.success('User was created successfully');
                    navigate(RoutePath.user.edit(data.data.data.id));
                }

                const new_data: UserType = data.data.data;
                const selected_perms: any[] = [];
                const selected_roles: any[] = [];

                new_data.permissions.map((perm) => {
                    selected_perms.push(perm.id);
                });
                new_data.available_permissions = new_data.permissions;
                new_data.permissions = selected_perms;

                new_data.roles.map((role) => {
                    selected_roles.push(role.id);
                });

                new_data.available_roles = new_data.roles;
                new_data.roles = selected_roles;

                //queryClient.invalidateQueries(['userData', { id: id }]);
                queryClient.setQueryData<UserType>(['userData', { id: id }], new_data);

                return data.data.data;
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

        RestAPI.getFormOptions(APIPath.role.index()).then((values) => {
            setRoles(values);
        });

        RestAPI.get(APIPath.others.countries()).then((response: any) => {
            setCountries(response.data.data);

            const rc: any[] = [];
            response.data.data.map((opt: any) => {
                rc.push({ value: opt['code'], title: opt['name'] });
            });

            setCountryOptions(rc);
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

    function impersonate() {
        RestAPI.get(APIPath.auth.impersonate(id || 0)).then((response: any) => {
            CookiesInterface.setAuth(response.data.access_token);
            // set user and authentication status after login
            context.update(
                { key: AppContextActionKeyEnum.user, value: response.data.user },
                { key: AppContextActionKeyEnum.authStatus, value: AuthStatusEnum.valid },
            );

            navigate('/announcements');
        });

        return false;
    }

    return (
        <FormComp
            onSubmit={handleSubmit(() => {
                mutator.mutate(getValues());
            })}
            title={id ? 'Update User: ' + getValues('email') : 'Create New User'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <SwitchComp name="active" control={control} title="Active" className={Styles.spacer} />

            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields()}
                    name="full_name"
                    control={control}
                    type="text"
                    title="Full Name"
                />
                <TextInputComp
                    className={Styles.fields()}
                    name="username"
                    control={control}
                    type="text"
                    title="Username"
                />
                <TextInputComp
                    className={Styles.fields(true)}
                    name="email"
                    control={control}
                    type="text"
                    title="Email"
                />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields(true)}
                    name="address"
                    control={control}
                    type="text"
                    title="Address"
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp className={Styles.fields()} name="city" control={control} type="text" title="City" />
                <TextInputComp
                    className={Styles.fields(true)}
                    name="postal_code"
                    control={control}
                    type="text"
                    title="Postal Code"
                />
            </div>
            <div className={Styles.row}>
                <SelectComp
                    className={Styles.fields()}
                    name="country_code"
                    control={control}
                    title="Country"
                    options={countryOptions}
                    placeholder="please select one"
                />
                <SelectComp
                    className={Styles.fields(true)}
                    name="province_code"
                    control={control}
                    options={provinceOptions}
                    title="Province"
                    placeholder="please select one"
                />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields()}
                    name="phone1"
                    control={control}
                    title="Primary Phone"
                    type="text"
                />
                <TextInputComp
                    className={Styles.fields(true)}
                    name="phone2"
                    control={control}
                    title="Secondary Phone"
                    type="text"
                />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <MultiSelect
                    options={roles}
                    className={Styles.fields(true)}
                    name="roles"
                    control={control}
                    title="User Groups"
                />
            </div>
            <div className={Styles.row}>
                <MultiSelect
                    options={permissions}
                    className={Styles.fields(true)}
                    name="permissions"
                    control={control}
                    title="User Permissions"
                />
            </div>
            <div className={Styles.row}>
                <ButtonComp onClick={impersonate}>Impersonate</ButtonComp>
            </div>
        </FormComp>
    );
};

export default UserFormComp;
