import { APIPath, RoutePath } from 'data';
import React from 'react';
import { RestAPI } from 'scripts';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { AnnouncementType } from 'types';
import { FormComp, TextEditorComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

// create/edit announcement page
const AnnouncementFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id } = useParams<any>();
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({
        title: yup.string().required(),
        body: yup.string().required(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['announcementData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<AnnouncementType>(APIPath.announcement.index(id))
                .then((response2) => {
                    const response: AnnouncementType = response2.data;

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

    const { handleSubmit, control, reset, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data || { title: '', body: '' },
    });

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.announcement.index(id), data);
            else apiCall = RestAPI.post(APIPath.announcement.index(), data);

            return apiCall;
        },
        {
            onError: (error: any) => {
                // An error happened!
                _.forEach(error.data.errors, (value, key) => {
                    setError(key, {
                        message: RestAPI.getErrorMessage('', Object.keys(value)[0], Object.values(value)[0]),
                    });
                });
                alertService.error(error.data.message || 'Something went wrong, please try again');
            },
            onSuccess: (data: any) => {
                // Boom baby!
                if (id) {
                    alertService.success('Announcement was updated successfully');
                } else {
                    alertService.success('Announcement was created successfully');
                    navigate(RoutePath.announcement.edit(data.data.data.id));
                }

                //queryClient.invalidateQueries('roleData');
                queryClient.setQueryData(['announcementData', { id: id }], data);
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
        <div>
            <FormComp
                onSubmit={handleSubmit(() => {
                    mutator.mutate(getValues());
                })}
                title={id ? 'Update Announcement' : 'Create New Announcement'}
                className={Styles.root}
                buttonTitle={id ? 'Update' : 'Create'}
            >
                <TextInputComp className={Styles.fields} name="title" control={control} type="text" title="Title" />
                <TextEditorComp className={Styles.fields} name="body" control={control} title="Body" />
            </FormComp>
        </div>
    );
};

export default AnnouncementFormComp;
