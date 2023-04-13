import { APIPath, RoutePath } from 'data';
import React, { useEffect } from 'react';
import { RestAPI } from 'scripts';
import { __FormStyle as Styles } from '../form.styles';
import { FormComp, ButtonComp } from 'utils';
import { useNavigate, useParams } from 'react-router-dom';
import { useQueryClient } from '@tanstack/react-query';
import { alertService } from 'helperComps/Alert/AlertService';
import { LeadsApi } from 'api/LeadsApi';

// create/edit service page
const ConfirmMessageFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id: id_str, action_id: action_id_str } = useParams<any>();
    const navigate = useNavigate();
    const queryClient = useQueryClient();

    const action_id: number = parseInt(action_id_str || '0');
    const id: number = parseInt(id_str || '0');

    const { data: leadActions } = LeadsApi.getActions(id);
    const { data: leadActionsData } = LeadsApi.getAction(id, action_id);

    function verified() {
        RestAPI.put(APIPath.lead.actions(id, action_id), [])
            .then((response: any) => {
                alertService.success(response.data.message || 'Lead was updated Successfully');
                queryClient.invalidateQueries();
            })
            .catch((error) => {
                alertService.error(error.response.data.message || 'Something went wrong, please try again');
                return;
            });

        return;
    }

    useEffect(() => {
        let redirect = true;
        leadActions?.forEach((action) => {
            if (action.id == action_id) {
                redirect = false;
            }
        });

        if (redirect) {
            navigate(RoutePath.lead.__index + '/' + id);
        }
    }, [leadActions]);

    function declined() {
        navigate(RoutePath.lead.__index + '/' + id);
        return;
    }

    if (!id || !action_id_str || !leadActionsData) {
        return <></>;
    }

    return (
        <FormComp title="Confirmation Required" className={Styles.root}>
            <div>{leadActionsData?.variables?.confirmation_message}</div>

            <div className={Styles.row}>
                <ButtonComp onClick={verified} className={Styles.column}>
                    YES
                </ButtonComp>
                <ButtonComp onClick={declined} className={Styles.column}>
                    NO
                </ButtonComp>
            </div>
        </FormComp>
    );
};

export default ConfirmMessageFormComp;
