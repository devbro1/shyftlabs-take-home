import { APIPath } from 'data';
import React from 'react';
import { RestAPI } from 'scripts';
import { __FormStyle as Styles } from './form.styles';
import { LeadType } from 'types';
import { FormComp, SelectComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useQuery } from '@tanstack/react-query';
import _ from 'lodash';
import { alertService } from 'helperComps/Alert/AlertService';

// create/edit lead page
const CustomerLeadFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const provinceOptions: any[] = [];
    const validationSchema = yup.object().shape({
        first_name: yup.string().required(),
        last_name: yup.string().required(),
        email: yup.string().required().email(),
        address: yup.string().required(),
        city: yup.string().required(),
        province_code: yup.string().required(),
        postal_code: yup.string().required(),
        service_id: yup.string().required(),
        phone1: yup.string().required(),
    });

    const { data: countries } = useQuery(['countriesList', {}] as const, ({}) => {
        return RestAPI.get<any>(APIPath.others.countries())
            .then((response2) => {
                return response2.data.data;
            })
            .catch((ex) => {
                throw ex;
            });
    });

    const { data: available_services } = useQuery(['servicesList', {}] as const, ({}) => {
        return RestAPI.get<any>(APIPath.service.index())
            .then((response2) => {
                return response2.data.data;
            })
            .catch((ex) => {
                throw ex;
            });
    });

    const { handleSubmit, control, setError, watch } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: { country_code: '' },
    });

    // show loading if this is edit page until getting data by api call
    // if (id && isLoading) {
    //     return (
    //         <div className={Styles.loading}>
    //             <FaSpinner size={48} className={Styles.loadingIcon} />
    //         </div>
    //     );
    // }

    const country_of_provinces = watch('country_code');
    if (countries) {
        const country = countries?.find((i: any) => i.code === country_of_provinces);
        country?.provinces.map((prov: any) => provinceOptions.push({ title: prov.name, value: prov.code }));
    }

    function submitForm(values: any) {
        return RestAPI.post<LeadType>(APIPath.lead.index(), values)
            .then((response: any) => {
                alertService.success(response.data.message);
                return response;
            })
            .catch((error) => {
                //TODO: just an idea, we can pass error value from api to the field, and let field deal with
                //      translating error to english for displaying
                _.forEach(error.data.errors, (value, key) => {
                    setError(key, {
                        message: RestAPI.getErrorMessage('', Object.keys(value)[0], Object.values(value)[0]),
                    });
                });
                alertService.error(error.data.message || 'Something went wrong, please try again');
            });
    }

    return (
        <FormComp
            onSubmit={handleSubmit((values) => {
                submitForm(values);
            })}
            title={''}
            className={Styles.root}
            buttonTitle="Sumbit"
        >
            <TextInputComp
                className={Styles.fields}
                name="first_name"
                control={control}
                type="text"
                title="First Name"
            />
            <TextInputComp className={Styles.fields} name="last_name" control={control} type="text" title="Last Name" />
            <TextInputComp className={Styles.fields} name="email" control={control} type="text" title="Email" />
            <TextInputComp className={Styles.fields} name="address" control={control} type="text" title="Address" />
            <TextInputComp className={Styles.fields} name="city" control={control} type="text" title="city" />
            <TextInputComp
                className={Styles.fields}
                name="postal_code"
                control={control}
                type="text"
                title="Postal/Zip Code"
            />
            <SelectComp
                className={Styles.fields}
                name="province_code"
                control={control}
                options={provinceOptions}
                title="Province"
                placeholder="- please select one -"
            />
            <SelectComp
                className={Styles.fields}
                name="country_code"
                control={control}
                title="Country"
                options={countries?.map((country: any) => {
                    return { value: country.code, title: country.name };
                })}
                placeholder="- please select one -"
            />
            <TextInputComp
                className={Styles.fields}
                name="phone1"
                control={control}
                type="text"
                title="Primary Phone Number"
            />
            <TextInputComp
                className={Styles.fields}
                name="phone1"
                control={control}
                type="text"
                title="Secondary Phone Number"
            />
            <SelectComp
                className={Styles.fields}
                name="service_id"
                control={control}
                options={available_services?.map((opt: any) => {
                    return { value: opt.id, title: opt.name };
                })}
                title="Desired Service"
                placeholder="- please select one -"
            />
        </FormComp>
    );
};

export default CustomerLeadFormComp;
