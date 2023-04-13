import { APIPath, RoutePath } from 'data';
import React, { useEffect } from 'react';
import { RestAPI } from 'scripts';
import { __FormStyle as Styles } from '../form.styles';
import { FormComp, ButtonComp, TextInputComp } from 'utils';
import { useNavigate, useParams } from 'react-router-dom';
import { useQueryClient } from '@tanstack/react-query';
import { alertService } from 'helperComps/Alert/AlertService';
import { LeadsApi } from 'api/LeadsApi';
import { FieldValues, useForm, useFieldArray } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from 'yup';

type PageParams = {
    id: string;
    action_id: string;
};

// create/edit service page
const FillInvoiceFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id, action_id } = useParams<PageParams>();
    if (!id || !action_id) {
        return <></>;
    }

    const navigate = useNavigate();
    const queryClient = useQueryClient();
    const { data: leadData } = LeadsApi.get(parseInt(id));
    const { data: leadActionsData } = LeadsApi.getAction(parseInt(id), parseInt(action_id));
    const validationSchema = yup.object().shape({});

    const { control, getValues, setValue, handleSubmit } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: {},
    });

    useEffect(() => {
        if (leadData && leadActionsData) {
            const old_invoice = leadData.invoices.find((element: any) => {
                return element.key == leadActionsData.variables.key;
            });

            if (old_invoice) {
                setValue('total', old_invoice.total);
                old_invoice.items.map((item: any) => {
                    append(item);
                });
            }
        }
    }, [leadData, leadActionsData]);

    const { fields, append, remove } = useFieldArray({
        name: 'items',
        control,
    });

    function submit() {
        RestAPI.put(APIPath.lead.actions(id, action_id), getValues())
            .then((response: any) => {
                alertService.success(response.data.message || 'Lead was updated Successfully');
                queryClient.invalidateQueries();
                navigate(RoutePath.lead.__index + '/' + id);
            })
            .catch((error) => {
                alertService.error(error.response.data.message || 'Something went wrong, please try again');
                return;
            });

        return;
    }

    function cancel() {
        navigate(RoutePath.lead.__index + '/' + id);
        return;
    }

    return (
        <FormComp
            title={leadActionsData?.alternative_name}
            className={Styles.root}
            onSubmit={handleSubmit(() => {
                submit();
            })}
            buttonTitle="Submit"
        >
            <div>{leadActionsData?.variables?.confirmation_message}</div>

            {fields.map((field, index) => {
                return (
                    <div className={Styles.row} key={field.id}>
                        {leadActionsData?.variables.item_fields.split(',').map((field2: string) => {
                            field2 = field2.trim();
                            const field2_name = field2.replaceAll(' ', '-');
                            return (
                                <TextInputComp
                                    className={Styles.column}
                                    name={`items.${index}.${field2_name}`}
                                    key={`items.${index}.${field2_name}`}
                                    title={field2}
                                    control={control}
                                />
                            );
                        })}
                        <ButtonComp onClick={() => remove(index)}>Delete</ButtonComp>
                    </div>
                );
            })}
            <div className={Styles.row}>
                <button
                    type="button"
                    onClick={() => {
                        const list: any = {};
                        leadActionsData?.variables.item_fields.split(',').map((field: string) => {
                            list[field] = '';
                        });
                        append(list);
                    }}
                >
                    Append
                </button>
            </div>
            <div className={Styles.row}>
                <TextInputComp className={Styles.column} name="total" title="Total" control={control} />
            </div>
            <div className={Styles.row}>
                <ButtonComp onClick={cancel} className={Styles.column}>
                    Cancel
                </ButtonComp>
                <ButtonComp onClick={submit} className={Styles.column}>
                    Submit
                </ButtonComp>
            </div>
        </FormComp>
    );
};

export default FillInvoiceFormComp;
