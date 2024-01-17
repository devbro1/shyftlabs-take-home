import { APIPath, RoutePath } from 'data';
import React from 'react';
import { __RestAPI as RestAPI } from 'scripts/api';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { StudentType } from 'types';
import { FormComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

const StudentFormComp: React.FC = () => {
    const id: number = parseInt(useParams<any>().id || '0');

    const navigate = useNavigate();

    const validationSchema = yup.object().shape({
        first_name: yup.string().required(),
        family_name: yup.string().required(),
        email: yup.string().email().required(),
        date_of_birth: yup.string().required(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery<StudentType, Error>(['studentData', { id: id }], (params: any) => {
        const [, { id }] = params.queryKey;

        if (id) {
            return RestAPI.get<StudentType>(APIPath.student.index(id))
                .then((response2) => {
                    const response: StudentType = response2.data;

                    reset(response);
                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return {} as StudentType;
        }
    });

    const { handleSubmit, control, reset, getValues, setError, watch } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

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
                if (id) {
                    alertService.success('Student was updated successfully');
                } else {
                    alertService.success('Student was created successfully');
                    navigate(RoutePath.student.edit(data.data.data.id));
                }

                queryClient.setQueryData<StudentType>(['studentData', { id: id }], data.data.data);

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
            title={id ? 'Update Student: ' + getValues('email') : 'Create New Student'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >

            <div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields()}
                    name="first_name"
                    control={control}
                    type="text"
                    title="First Name"
                />
                <TextInputComp
                    className={Styles.fields()}
                    name="family_name"
                    control={control}
                    type="text"
                    title="Family Name"
                />
            </div><div className={Styles.row}>
                <TextInputComp
                    className={Styles.fields()}
                    name="email"
                    control={control}
                    type="text"
                    title="Email"
                />
                <TextInputComp
                    className={Styles.fields()}
                    name="date_of_birth"
                    control={control}
                    type="text"
                    title="Date Of Birth"
                />
            </div>
        </FormComp>
    );
};

export default StudentFormComp;
