import { APIPath } from 'data';
import { RestAPI } from 'scripts';
import { useQuery, UseQueryOptions } from '@tanstack/react-query';
import { LeadType, LeadActionType } from 'types';

function get(leadId: number, params: UseQueryOptions<LeadType> = {}) {
    params['queryKey'] = ['leadData', { id: leadId }];
    params['suspense'] = true;
    params['queryFn'] = ({ queryKey }): Promise<LeadType> => {
        const [, { id }]: any = queryKey;
        return RestAPI.get<LeadType>(APIPath.lead.index(id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery<LeadType>(params);
}

function getAction(leadId: number, actionId: number, params: UseQueryOptions<LeadActionType> = {}) {
    params['queryKey'] = ['leadActionData', { lead_id: leadId, action_id: actionId }];
    params['suspense'] = true;
    params['queryFn'] = ({ queryKey }): Promise<LeadActionType> => {
        const [, { lead_id, action_id }]: any = queryKey;
        return RestAPI.get<LeadActionType>(APIPath.lead.actions(lead_id, action_id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };
    return useQuery(params);
}

function getActions(leadId: number, params: UseQueryOptions<LeadActionType[]> = {}) {
    params['queryKey'] = ['leadActionsData', { lead_id: leadId }];
    params['suspense'] = true;
    params['queryFn'] = ({ queryKey }): Promise<LeadActionType[]> => {
        const [, { lead_id }]: any = queryKey;
        return RestAPI.get<LeadActionType[]>(APIPath.lead.actions(lead_id))
            .then((response) => {
                return response.data;
            })
            .catch((ex) => {
                throw ex;
            });
    };

    return useQuery(params);
}

export const LeadsApi = {
    get,
    getAction,
    getActions,
};
