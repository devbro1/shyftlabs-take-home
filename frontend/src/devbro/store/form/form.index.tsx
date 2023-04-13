import { APIPath, RoutePath } from 'data';
import React, { useEffect, useState } from 'react';
import { RestAPI } from 'scripts';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { StoreType } from 'types';
import { FormComp, SelectComp, SwitchComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

// create/edit store page
const StoreFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id } = useParams<any>();
    const navigate = useNavigate();
    const [countries, setCountries] = useState<any[]>([]);
    const [countryOptions, setCountryOptions] = useState<any[]>([]);
    const provinceOptions: any[] = [];

    const validationSchema = yup.object().shape({
        active: yup.boolean().required(),
        name: yup.string().required(),
        latitude: yup
            .number()
            .transform((value) => (isNaN(value) ? undefined : value))
            .nullable(),
        longitude: yup
            .number()
            .transform((value) => (isNaN(value) ? undefined : value))
            .nullable(),
        coverage_radius: yup.number().min(0),
        address: yup.string(),
        city: yup.string(),
        postal_code: yup.string(),
        province_code: yup.string(),
        country_code: yup.string().required(),
        store_no: yup.string().required(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['storeData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<StoreType>(APIPath.store.index(id))
                .then((response2) => {
                    const response: StoreType = response2.data;

                    reset(response);
                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return null;
        }
    });
    const formData: any = data || { latitude: '', longitude: '', coverage_radius: 0, country_code: 'CA' };

    const { handleSubmit, control, reset, getValues, setError, watch } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: formData,
    });

    const country_of_provinces = watch('country_code');
    if (countries) {
        const country = countries?.find((i) => i.code === country_of_provinces);
        country?.provinces.map((prov: any) => provinceOptions.push({ title: prov.name, value: prov.code }));
    }

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.store.index(id), data);
            else apiCall = RestAPI.post(APIPath.store.index(), data);

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
                    alertService.success('Store was updated successfully');
                } else {
                    alertService.success('Store was created successfully');
                    navigate(RoutePath.store.edit(data.data.data.id));
                }

                //queryClient.invalidateQueries('roleData');
                queryClient.setQueryData(['storeData', { id: id }], data);
            },
            // onSettled: (data, error, variables, context) => {
            //     // Error or success... doesn't matter!
            // },b
        },
    );

    useEffect(() => {
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

    return (
        <FormComp
            onSubmit={handleSubmit(() => {
                mutator.mutate(getValues());
            })}
            title={id ? 'Update Store: ' + getValues('name') : 'Create New Store'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <SwitchComp name="active" control={control} title="Active" className={Styles.spacer} />
            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields()}
                    name="store_no"
                    control={control}
                    type="text"
                    title="Store Number"
                />
                <TextInputComp
                    className={Styles.fields(true)}
                    name="name"
                    control={control}
                    type="text"
                    title="Store Name"
                />
            </div>
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
            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields()}
                    name="latitude"
                    control={control}
                    title="Latitude"
                    type="number"
                />
                <TextInputComp
                    className={Styles.fields(true)}
                    name="longitude"
                    control={control}
                    title="Longitude"
                    type="number"
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields()}
                    name="coverage_radius"
                    control={control}
                    title="Coverage Radius"
                    type="number"
                />
            </div>
        </FormComp>
    );
};

export default StoreFormComp;
