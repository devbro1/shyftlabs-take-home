import { APIPath } from 'data';
import React from 'react';
import { RestAPI } from 'scripts';
import { __styles as Styles } from './form.styles';
import { WorkflowType } from 'types';
import { FormComp, TextInputComp, SelectComp, ButtonComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm, useFieldArray } from 'react-hook-form';
import { useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';

function ActionComp(props: any) {
    const { data: action_info, isLoading } = useQuery(
        ['actionData', { id: props.action_info.action_id }] as const,
        ({ queryKey }) => {
            const [, { id }] = queryKey;
            return RestAPI.get<WorkflowType>(APIPath.action.index(id))
                .then((response: any) => {
                    return response.data;
                })
                .catch((ex) => {
                    throw ex;
                });
        },
    );

    if (isLoading) {
        return <div>LOADING</div>;
    }

    const action_variables: any[] = [];
    _.forEach(action_info.workflow_node_variables, (v) => {
        action_variables.push(
            <div className="col-span-6">
                <TextInputComp
                    name={`actions.${props.index}.variables.${v.name}`}
                    title={v.name}
                    description={v.description}
                    control={props.control}
                />
            </div>,
        );
    });

    return (
        <>
            <div className="col-span-6">
                <hr />
            </div>

            <div className="col-span-5">
                Action {props.index + 1} - {action_info.name}
            </div>
            <div className="col-span-1 ">
                <ButtonComp
                    onClick={() => {
                        props.remove();
                    }}
                >
                    Delete
                </ButtonComp>
            </div>
            <div className="col-span-6">
                <TextInputComp
                    name={`actions.${props.index}.alternative_name`}
                    title="Action Name"
                    control={props.control}
                />
            </div>
            <div className="col-span-6">
                <SelectComp
                    name={`actions.${props.index}.permission_id`}
                    title="Required Permission"
                    control={props.control}
                    options={props.available_permissions}
                    placeholder="- Optional Permission -"
                />
            </div>
            <div className="col-span-6">
                <SelectComp
                    name={`actions.${props.index}.status_to_id`}
                    title="Changes Status to"
                    options={props.next_statuses}
                    control={props.control}
                    placeholder="- Optional status change -"
                />
            </div>
            {action_variables}
        </>
    );
}

const ActionFormComp: React.FC = () => {
    const id: number = parseInt(useParams<any>().action_id || '0');
    const workflow_id: number = parseInt(useParams<any>().id || '0');

    const validationSchema = yup.object().shape({
        label: yup.string().required(),
    });

    const queryClient = useQueryClient();

    const { data: availableActionsOptions } = useQuery(['availableActionsOptionsList'] as const, () => {
        return RestAPI.get<Array<any>>(APIPath.action.index())
            .then((response: any) => {
                const rc: any[] = [];

                _.forEach(response.data.data, (value) => {
                    rc.push({ title: value.name, value: value.id });
                });

                return rc;
            })
            .catch((ex) => {
                throw ex;
            });
    });

    const { data: formData, isLoading } = useQuery(['workflowNodeData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<WorkflowType>(APIPath.workflow.node(workflow_id, id))
                .then((response: any) => {
                    reset(response.data);
                    return response.data;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return {};
        }
    });
    const formData2: any = formData;

    const { data: permissions } = useQuery(['permissionsList'] as const, () => {
        return RestAPI.get<WorkflowType>(APIPath.permission.index())
            .then((response: any) => {
                return response.data.data;
            })
            .catch((ex) => {
                throw ex;
            });
    });

    const { handleSubmit, control, reset, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
    });

    const {
        fields: action_fields,
        append,
        remove,
    } = useFieldArray({
        control,
        name: 'actions',
    });

    const mutator = useMutation(
        (data: any) => {
            return RestAPI.put(APIPath.workflow.node(workflow_id, id), data);
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
            onSuccess: () => {
                alertService.success('Node was updated successfully');

                queryClient.invalidateQueries(['workflowNodeData', { id: id }]);
                //queryClient.setQueryData(['workflowNodeData', { id: id }], data);
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

    function addAction() {
        if (getValues().available_action) {
            append({ action_id: getValues().available_action, permission_id: null, status_to_id: null });
        }
    }

    return (
        <FormComp
            onSubmit={handleSubmit(() => {
                mutator.mutate(getValues());
            })}
            title={id ? 'Update workflow: ' + getValues('name') : 'Create Workflow'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <TextInputComp className={Styles.fields} name="label" control={control} type="text" title="Label" />

            <SelectComp
                className={Styles.fields}
                name="available_action"
                control={control}
                title="Available Action"
                options={availableActionsOptions}
                placeholder="- Select one to add -"
            />
            <ButtonComp onClick={addAction}>Add Action</ButtonComp>

            {action_fields.map((item, index) => {
                const actionProps: any = {};
                actionProps.index = index;
                actionProps.action_info = item;
                actionProps.control = control;
                actionProps.remove = () => {
                    remove(index);
                    return;
                };
                actionProps.available_permissions = permissions?.map((opt: any) => {
                    return { value: opt.id, title: opt.name };
                });
                actionProps.next_statuses = formData2?.next_statuses?.map((opt: any) => {
                    return { value: opt.id, title: opt.label };
                });

                return <ActionComp key={`ActionComp-${index}`} {...actionProps} />;
            })}
        </FormComp>
    );
};

export default ActionFormComp;
