import { APIPath, RoutePath } from 'data';
import React, { useEffect, useState } from 'react';
import { RestAPI } from 'scripts';
import { __FormStyle as Styles } from './form.styles';
import { CompanyType } from 'types';
import { FormComp, SwitchComp, TextInputComp, FileInputComp, SelectComp, MultiSelect, ButtonComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';
import { alertService } from 'helperComps/Alert/AlertService';

// create/edit company page
const CompanyFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id } = useParams<any>();

    const [users, setUsers] = useState<any[]>([]);
    const [countryOptions, setCountryOptions] = useState<any[]>([]);
    const provinceOptions: any[] = [];

    const navigate = useNavigate();
    const validationSchema = yup.object().shape({
        name: yup.string().required(),
        active: yup.boolean().required(),
        logo_file_id: yup.mixed().required().notOneOf(['Uploading...'], 'Please wait for file to upload'),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['companyData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<CompanyType>(APIPath.company.index(id))
                .then((response2) => {
                    const response: CompanyType = response2.data;

                    response.owner_ids = _.map(response.owners, 'id');
                    response.employee_ids = _.map(response.employees, 'id');

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

    const { reset, handleSubmit, control, getValues, setError, clearErrors } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.company.index(id), data);
            else apiCall = RestAPI.post(APIPath.company.index(), data);

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
                data.data.data.owner_ids = _.map(data.data.data.owners, 'id');
                data.data.data.employee_ids = _.map(data.data.data.employees, 'id');
                // Boom baby!
                if (id) {
                    alertService.success('Company was updated successfully');
                } else {
                    alertService.success('Company was created successfully');
                    navigate(RoutePath.company.edit(data.data.data.id));
                }

                //queryClient.invalidateQueries('roleData');
                queryClient.setQueryData(['companyData', { id: id }], data);
            },
            // onSettled: (data, error, variables, context) => {
            //     // Error or success... doesn't matter!
            // },b
        },
    );

    useEffect(() => {
        RestAPI.get(APIPath.others.countries()).then((response: any) => {
            const rc: any[] = [];
            response.data.data.map((opt: any) => {
                rc.push({ value: opt['code'], title: opt['name'] });
            });

            setCountryOptions(rc);
        });

        RestAPI.getFormOptions(APIPath.user.index() + '?page[per_page]=1000', 'id', 'full_name').then((vals) => {
            setUsers(vals);
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
            title={id ? 'Update Company: ' + getValues('name') : 'Create New Company'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <SwitchComp name="active" control={control} title="Active" className={Styles.fields} />
            <TextInputComp className={Styles.fields} name="name" control={control} type="text" title="Name" />

            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields}
                    name="phone1"
                    control={control}
                    title="Primary Phone"
                    type="text"
                />
                <TextInputComp className={Styles.fields} name="website" control={control} title="Website" type="text" />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <TextInputComp className={Styles.fields} name="address" control={control} type="text" title="Address" />
            </div>
            <div className={Styles.row}>
                <TextInputComp className={Styles.fields} name="city" control={control} type="text" title="City" />
                <TextInputComp
                    className={Styles.fields}
                    name="postal_code"
                    control={control}
                    type="text"
                    title="Postal Code"
                />
            </div>
            <div className={Styles.row}>
                <SelectComp
                    className={Styles.fields}
                    name="country_code"
                    control={control}
                    title="Country"
                    options={countryOptions}
                    placeholder="please select one"
                />
                <SelectComp
                    className={Styles.fields}
                    name="province_code"
                    control={control}
                    options={provinceOptions}
                    title="Province"
                    placeholder="please select one"
                />
            </div>

            <div className={Styles.row}>
                <FileInputComp
                    className={Styles.fields}
                    name="logo_file_id"
                    control={control}
                    title="Logo"
                    setError={setError}
                    clearErrors={clearErrors}
                />
            </div>
            <div className={Styles.row}>
                <MultiSelect
                    options={users}
                    className={Styles.fields}
                    name="owner_ids"
                    control={control}
                    title="Owners"
                />
            </div>
            <div className={Styles.row}>
                <MultiSelect
                    options={users}
                    className={Styles.fields}
                    name="employee_ids"
                    control={control}
                    title="Employees"
                />
            </div>

            {id ? (
                <div className={Styles.row}>
                    <ButtonComp
                        onClick={() => {
                            const params = new URLSearchParams();
                            params.append('company_id', id.toString());
                            navigate(RoutePath.service.availability(params));
                        }}
                    >
                        Edit Service Coverage
                    </ButtonComp>
                </div>
            ) : null}
        </FormComp>
    );
};

export default CompanyFormComp;
