import { APIPath, RoutePath } from 'data';
import React from 'react';
import { __RestAPI as RestAPI } from 'scripts/api';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { SelectOption, ResultType } from 'types';
import { FormComp, SingleSelect, SelectComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';
import { StudentsApi } from 'api/StudentsApi';
import { CoursesApi } from 'api/CoursesApi';


const ResultFormComp: React.FC = () => {
    const id: number = parseInt(useParams<any>().id || '0');

    const { data: studentsOptions } = StudentsApi.options();
    const { data: coursesOptions } = CoursesApi.options();

    const score_options : SelectOption[] = [];
    score_options.push({"value":"","title":" --- "});
    score_options.push({"value":"A","title":"A"});
    score_options.push({"value":"B","title":"B"});
    score_options.push({"value":"C","title":"C"});
    score_options.push({"value":"D","title":"D"});
    score_options.push({"value":"E","title":"E"});
    score_options.push({"value":"F","title":"F"});


    const validationSchema = yup.object().shape({
        student_id: yup.number().required(),
        course_id: yup.number().required(),
        score: yup.string().required(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery<ResultType, Error>(['resultData', { id: id }], (params: any) => {
        const [, { id }] = params.queryKey;

        if (id) {
            return RestAPI.get<ResultType>(APIPath.result.index(id))
                .then((response2) => {
                    const response: ResultType = response2.data;

                    reset(response);
                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return {} as ResultType;
        }
    });

    const { handleSubmit, control, reset, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: {"score":"","student_id":"","course_id":""},
    });

    const mutator = useMutation(
        (data: any) => {
            console.log(data);
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.result.index(id), data);
            else apiCall = RestAPI.post(APIPath.result.index(), data);

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
                    alertService.success('Result was updated successfully');
                } else {
                    alertService.success('Result was created successfully');
                    reset();
                }

                queryClient.setQueryData<ResultType>(['resultData', { id: id }], data.data.data);

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
            title={id ? 'Update Result: ' : 'Create New Result'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <div className={Styles.row}>
                <SingleSelect
                    options={studentsOptions}
                    name="student_id"
                    className={"mb-3 w-full p-1"}
                    control={control}
                    title="Student Name"
                    description={"student list"}
                />
            </div>
            <div className={Styles.row}>
                <SingleSelect
                    className={Styles.fields()}
                    name="course_id"
                    control={control}
                    type="text"
                    title="Course"
                    options={coursesOptions}
                />
            </div>
            <div className={Styles.row}>
                <SelectComp 
                    name="score"
                    control={control}
                    title="Score"
                    options={score_options} />
            </div>
        </FormComp>
    );
};

export default ResultFormComp;
