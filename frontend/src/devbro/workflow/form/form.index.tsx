import { APIPath, RoutePath } from 'data';
import React from 'react';
import { RestAPI } from 'scripts';
import { __styles as Styles } from './form.styles';
import { WorkflowType } from 'types';
import { FlowChartComp, FormComp, TextInputComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { alertService } from 'helperComps/Alert/AlertService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import _ from 'lodash';
import { EditableNode } from './CustomFlowNodes';

// edit workflow page
const WorkflowFormComp: React.FC = () => {
    const id: number = parseInt(useParams<any>().id || '0');
    const navigate = useNavigate();

    const validationSchema = yup.object().shape({
        name: yup.string().required(),
        description: yup.string(),
        active: yup.boolean(),
        flow: yup.object().shape({
            nodes: yup.array(),
            edges: yup.array(),
        }),
    });

    const queryClient = useQueryClient();

    const { data, isLoading } = useQuery(['workflowData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<WorkflowType>(APIPath.workflow.index(id))
                .then((response2: any) => {
                    const response: WorkflowType = response2.data;
                    response.flow = { edges: [], nodes: [] };

                    if (response2.data.edges.length) {
                        response.flow.edges = response2.data.edges.map((i: any) => ({
                            id: 'edge-' + i.id.toString(),
                            source: i.source_id.toString(),
                            target: i.target_id.toString(),
                            data: {
                                id: i.id,
                            },
                        }));
                    }

                    if (response2.data.nodes.length) {
                        response.flow.nodes = response2.data.nodes.map((i: any) => ({
                            id: i.id.toString(),
                            position: { x: i.position_x, y: i.position_y },
                            type: i.type,
                            data: {
                                id: i.id,
                                label: i.label,
                                serverSideSaved: true,
                                onNameChanged: onNameChanged,
                                editAction: () => {
                                    navigate(RoutePath.workflow.editNode(id, i.id));
                                },
                            },
                        }));
                    }

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

    const { handleSubmit, control, reset, getValues, setError, setValue } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: data,
    });

    function onNameChanged(data: any) {
        const val: any = getValues('flow');
        _.forEach(val.nodes, (i: any, index: number) => {
            if (i.id === data.id) {
                val.nodes[index].data.label = data.label;
            }
        });
        setValue('flow', val);
    }

    const mutator = useMutation(
        (data: any) => {
            if (!id) {
                return RestAPI.post(APIPath.workflow.index(), data);
            }

            data.nodes = data.flow.nodes;
            data.edges = data.flow.edges;

            return RestAPI.put(APIPath.workflow.index(id), data);
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
                    alertService.success('Workflow was updated successfully');
                } else {
                    alertService.success('Workflow was created successfully');
                    navigate(RoutePath.workflow.edit(data.data.data.id));
                }

                queryClient.invalidateQueries(['workflowData', { id: id }]);
                //queryClient.setQueryData(['workflowData', { id: id }], data);
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
            title={id ? 'Update workflow: ' + getValues('name') : 'Create Workflow'}
            className={Styles.root}
            buttonTitle={id ? 'Update' : 'Create'}
        >
            <TextInputComp className={Styles.fields} name="name" control={control} type="text" title="Name" />
            <TextInputComp
                className={Styles.fields}
                name="description"
                control={control}
                type="text"
                title="Description"
            />
            {id != 0 && (
                <FlowChartComp
                    className={Styles.fields}
                    name="flow"
                    control={control}
                    title="Flow Chart"
                    onNameChanged={onNameChanged}
                    nodeTypes={{
                        EditableNodeInput: EditableNode,
                        EditableNodeDefault: EditableNode,
                        EditableNodeOutput: EditableNode,
                    }}
                />
            )}
        </FormComp>
    );
};

export default WorkflowFormComp;
