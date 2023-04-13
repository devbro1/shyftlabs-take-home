import { RoutePath } from 'data';
import React, { useEffect, useState } from 'react';
import { __FormStyle as Styles } from './form.styles';
import { LeadActionType } from 'types';
import { ButtonComp } from 'utils';
import { useNavigate, useParams } from 'react-router-dom';
import { JSONTree } from 'react-json-tree';
import { InfoTable, __InfoRowType } from 'helperComps';
import { __CustomerType, __LeadType } from 'types/models';
import { LeadsApi } from 'api/LeadsApi';

// create/edit lead page
const LeadFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id } = useParams<any>();
    const navigate = useNavigate();
    const [customerInfo, setCustomerInfo] = useState<__InfoRowType[]>([]);
    const [LeadInfo, setLeadInfo] = useState<__InfoRowType[]>([]);

    if (!id) {
        return <></>;
    }

    const { data: leadData } = LeadsApi.get(parseInt(id));

    useEffect(() => {
        if (leadData) {
            const c = [];
            const l: __LeadType = leadData as __LeadType;
            const customer: __CustomerType = l.customer;
            c.push({ title: 'First Name', value: customer.first_name });
            c.push({ title: 'last Name', value: customer.last_name });
            c.push({ title: 'Email', value: customer.email });
            c.push({ title: 'Primary Phone Number', value: customer.phone1 });
            c.push({ title: 'Secondary Phone Number', value: customer.phone2 });
            c.push({ title: 'Address', value: customer.address });
            c.push({ title: 'City', value: customer.city });
            c.push({ title: 'Province', value: customer.province_code });
            c.push({ title: 'Postal Code', value: customer.postal_code });
            c.push({ title: 'Country', value: customer.country_code });

            setCustomerInfo(c);

            const li = [];
            li.push({ title: 'Lead ID', value: l.id });
            li.push({ title: 'Status', value: l.status.label });
            li.push({ title: 'Service', value: l.service.name });
            li.push({ title: 'Store', value: l.store.name + ' ' + l.store.store_no });
            li.push({ title: 'Company', value: l.owners[0].provider.companies[0].name });
            setLeadInfo(li);
        }
    }, [leadData]);

    const { data: leadActionsData } = LeadsApi.getActions(parseInt(id));

    useEffect(() => {
        leadActionsData?.forEach((action: LeadActionType) => {
            if (action.variables?.force_action) {
                const url =
                    RoutePath.lead.__index + '/' + id + '/actions/' + action.action.frontend_uri + '/' + action.id;

                navigate(url);
            }
        });
    }, [leadActionsData]);

    if (!leadActionsData) {
        return <></>;
    }

    function ActionButton(aprops: any) {
        return (
            <ButtonComp
                onClick={() => {
                    const url =
                        RoutePath.lead.__index +
                        '/' +
                        id +
                        '/actions/' +
                        aprops.action.action.frontend_uri +
                        '/' +
                        aprops.action.id;

                    navigate(url);
                    return;
                }}
            >
                {aprops.action.alternative_name}
            </ButtonComp>
        );
    }

    return (
        <>
            {leadActionsData.map((item: any, index: number) => {
                return <ActionButton key={`action-button-` + index} action={item} index={index} />;
            })}

            <div className={Styles.row}>
                <div className={Styles.fields}>
                    <InfoTable title="Customer Information" data={customerInfo} />
                </div>
                <div className={Styles.fields}>
                    <InfoTable title="Lead Information" data={LeadInfo} />
                </div>
            </div>

            <JSONTree data={leadData} />
            <JSONTree data={leadActionsData} />
        </>
    );
};

export default LeadFormComp;
