import { APIPath, RoutePath } from 'data';
import React from 'react';
import { RestAPI } from 'scripts';
import { __FormStyle as Styles } from '../form.styles';
import { LeadType } from 'types';
import { FormComp, DateTimePickerComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
import { useQuery, useQueryClient } from '@tanstack/react-query';
import { alertService } from 'helperComps/Alert/AlertService';

// create/edit service page
const SetDateFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id, action_id } = useParams<any>();
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({});

    const queryClient = useQueryClient();

    const { isLoading } = useQuery(['leadData', { id: id }] as const, ({ queryKey }) => {
        const [, { id }] = queryKey;
        if (id) {
            return RestAPI.get<LeadType>(APIPath.lead.index(id))
                .then((response) => {
                    return response.data;
                })
                .catch((ex) => {
                    throw ex;
                });
        } else {
            return {};
        }
    });

    const { data: leadActionsData2, isLoading: isLoading2 } = useQuery(
        ['leadActionsData', { lead_id: id, action_id: action_id }] as const,
        ({ queryKey }) => {
            const [, { lead_id, action_id }] = queryKey;
            if (id) {
                return RestAPI.get<LeadType>(APIPath.lead.actions(lead_id, action_id))
                    .then((response) => {
                        return response.data;
                    })
                    .catch((ex) => {
                        throw ex;
                    });
            } else {
                return {};
            }
        },
    );
    const leadActionsData: any = leadActionsData2;

    const { handleSubmit, control, getValues } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: {},
    });

    // show loading if this is edit page until getting data by api call
    if (isLoading2 || isLoading) {
        return (
            <div className={Styles.loading}>
                <FaSpinner size={48} className={Styles.loadingIcon} />
            </div>
        );
    }

    function submitForm() {
        RestAPI.put(APIPath.lead.actions(id, action_id), getValues())
            .then((response: any) => {
                alertService.success(response.data.message || 'Lead was updated Successfully');
                queryClient.invalidateQueries(['leadData', { id: id }]);
                queryClient.invalidateQueries(['leadActionsList', { lead_id: id }]);
                navigate(RoutePath.lead.__index + '/' + id);
            })
            .catch((error) => {
                alertService.error(error.response.data.message || 'Something went wrong, please try again');
                return;
            });

        return;
    }

    return (
        <FormComp
            title="Confirmation Required"
            className={Styles.root}
            onSubmit={handleSubmit(() => {
                submitForm();
            })}
            buttonTitle="Submit"
        >
            <div>{leadActionsData?.variables?.confirmation_message}</div>

            <DateTimePickerComp control={control} name="dt" showTime={leadActionsData.hasTime} />
        </FormComp>
    );
};

export default SetDateFormComp;
