import { APIPath, RoutePath } from 'data';
import React from 'react';
import { RestAPI } from 'scripts';
import { __AnnouncementFormStyle as Styles } from './form.styles';
import { ActionType } from 'types';
import { FormComp, SwitchComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm, useFieldArray } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

// create/edit announcement page
const ActionFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id } = useParams<any>();
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({
        name: yup.string().required(),
        active: yup.boolean(),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['actionData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<ActionType>(APIPath.action.index(id))
                .then((response2) => {
                    const response: ActionType = response2.data;

                    _.sortBy(response.action_variables, (o: any) => {
                        return o.name;
                    }).map((value) => {
                        value.original_id = value.id;
                        actionVariableFields.append(value);
                    });

                    //delete response.action_variables;
                    reset(response);

                    return response;
                })
                .catch((ex) => {
                    throw ex;
                });
        }
        return null;
    });
    const formData: any = data || { name: '', boolean: false };

    const { handleSubmit, control, reset, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: formData,
    });

    //const { fields, append, prepend, remove, swap, move, insert } = useFieldArray({
    const actionVariableFields = useFieldArray({
        control, // control props comes from useForm (optional: if you are using FormContext)
        name: 'action_variables', // unique name for your Field Array
    });

    const mutator = useMutation(
        (data: any) => {
            let apiCall;
            if (id) apiCall = RestAPI.put(APIPath.action.index(id), data);
            else apiCall = RestAPI.post(APIPath.action.index(), data);

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
                    alertService.success('Action was updated successfully');
                } else {
                    alertService.success('Action was created successfully');
                    navigate(RoutePath.action.edit(data.data.data.id));
                }

                //queryClient.invalidateQueries('roleData');
                queryClient.setQueryData(['actionData', { id: id }], data);
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
            title={id ? 'Update Action: ' + getValues('name') : 'Create New Action'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <SwitchComp name="active" control={control} title="Active" className={Styles.fields} />
            <TextInputComp className={Styles.fields} name="name" control={control} type="text" title="Name" />

            {_.sortBy(actionVariableFields.fields, (o: any) => {
                return o.name;
            }).map((field: any, index) => {
                return (
                    <TextInputComp
                        key={field.id}
                        className={Styles.fields}
                        name={`action_variables.${index}.value`}
                        control={control}
                        type="text"
                        title={field.name}
                        description={field.description}
                    />
                );
            })}
        </FormComp>
    );
};

export default ActionFormComp;
