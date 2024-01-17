import { APIPath, RoutePath } from 'data';
import React from 'react';
import { __RestAPI as RestAPI } from 'scripts/api';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { TranslationType } from 'types';
import { FormComp, TextInputComp, SelectComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

// create/edit announcement page
const TranslationFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id } = useParams<any>();
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({
        key: yup.string().required(),
        translation: yup.string(),
        language: yup.string(),
    });
    const languages = [
        { value: 'en', title: 'English' },
        { value: 'fr', title: 'French' },
        { value: 'es', title: 'Spanish' },
    ];

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['translationData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<TranslationType>(APIPath.translation.index(id))
                .then((response2) => {
                    const response: TranslationType = response2.data;

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

    const { data: namespacesOptions } = useQuery(['TranslationNamespacesList'] as const, () => {
        return RestAPI.get<Array<any>>(APIPath.translation.namespaces())
            .then((response: any) => {
                const rc: any[] = [];

                _.forEach(response.data, (value, index) => {
                    rc.push({ title: value, value: index });
                });

                return rc;
            })
            .catch((ex) => {
                throw ex;
            });
    });

    const { handleSubmit, control, reset, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.translation.index(id), data);
            else apiCall = RestAPI.post(APIPath.translation.index(), data);

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
                    alertService.success('Translation was updated successfully');
                } else {
                    alertService.success('Translation was created successfully');
                    navigate(RoutePath.translation.edit(data.data.data.id));
                }

                queryClient.setQueryData(['translationData', { id: id }], data);
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
            title={id ? 'Update Translation: ' + getValues('key') : 'Create New Translation'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <SelectComp
                className={Styles.fields}
                name="namespace"
                control={control}
                title="Namespace"
                options={namespacesOptions}
                placeholder="- Select one -"
            />
            <TextInputComp className={Styles.fields} name="key" control={control} type="text" title="Key" />
            <TextInputComp
                className={Styles.fields}
                name="translation"
                control={control}
                type="text"
                title="Translation"
            />
            <SelectComp
                className={Styles.fields}
                name="language"
                control={control}
                title="Language"
                options={languages}
                placeholder="please select one"
            />
        </FormComp>
    );
};

export default TranslationFormComp;
