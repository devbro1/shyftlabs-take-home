import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __ListStyle as Styles } from './list.styles';
import { LeadType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// leads list page
const LeadListComp: React.FC = () => {
    // data table component column prop
    const column: any[] = [
        {
            title: 'ID',
            sortable: true,
            filter: true,
            field: 'id',
            value: (row: LeadType) => (
                <Link className={Styles.link} to={RoutePath.lead.edit(row.id)}>
                    {row.id}
                </Link>
            ),
            stringContent: (obj: LeadType) => obj.id,
        },
        {
            title: 'First Name',
            sortable: true,
            filter: true,
            field: 'customer.first_name',
            value: (row: LeadType) => {
                return row.customer.first_name;
            },
            stringContent: (obj: LeadType) => obj.customer.first_name,
        },
        {
            title: 'Last Name',
            sortable: true,
            filter: true,
            field: 'customer.last_name',
            value: (row: LeadType) => {
                return row.customer.last_name;
            },
            stringContent: (obj: LeadType) => obj.customer.last_name,
        },
        {
            title: 'Service',
            sortable: true,
            filter: true,
            field: 'service.name',
            value: (row: LeadType) => {
                return row.service.name;
            },
            stringContent: (obj: LeadType) => obj.service.name,
        },
        {
            title: 'Status',
            sortable: true,
            filter: true,
            field: 'status.label',
            value: (row: LeadType) => {
                return row.status.label;
            },
            stringContent: (obj: LeadType) => obj.status.label,
        },
    ];

    // render data table
    return <DataTableComp {...tablePropsProvider(APIPath.lead.index())} columns={column} />;
};

export default LeadListComp;
