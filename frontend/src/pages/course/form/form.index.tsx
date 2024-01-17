import { APIPath } from 'data';
import React from 'react';
import { __RestAPI as RestAPI } from 'scripts/api';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { CourseType } from 'types';
import { FormComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

const CourseFormComp: React.FC = () => {
    const id: number = parseInt(useParams<any>().id || '0');

    const validationSchema = yup.object().shape({
        name: yup.string().required(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery<CourseType, Error>(['courseData', { id: id }], (params: any) => {
        const [, { id }] = params.queryKey;

        if (id) {
            return RestAPI.get<CourseType>(APIPath.course.index(id))
                .then((response2) => {
                    const response: CourseType = response2.data;

                    reset(response);
                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return {} as CourseType;
        }
    });

    const { handleSubmit, control, reset, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.course.index(id), data);
            else apiCall = RestAPI.post(APIPath.course.index(), data);

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
                if (id) {
                    alertService.success('Course was updated successfully');
                } else {
                    alertService.success('Course was created successfully');
                    reset();
                }

                queryClient.setQueryData<CourseType>(['courseData', { id: id }], data.data.data);

                return data.data.data;
            },
        },
    );

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
            title={id ? 'Update Course: ' + getValues('name') : 'Create New Course'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields()}
                    name="name"
                    control={control}
                    type="text"
                    title="Name"
                />
              
            </div>
        </FormComp>
    );
};

export default CourseFormComp;
