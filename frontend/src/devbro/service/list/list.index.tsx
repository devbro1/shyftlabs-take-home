import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { ServiceType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// services list page
const ServiceListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            title: 'id',
            sortable: true,
            filter: true,
            field: 'ID',
            value: (row: ServiceType) => (
                <Link className={Styles.link} to={RoutePath.service.edit(row.id)}>
                    {row.id}
                </Link>
            ),
            stringContent: (obj: ServiceType) => obj.name,
        },
        {
            title: 'Name',
            sortable: true,
            filter: true,
            field: 'name',
            value: (row: ServiceType) => {
                return row.name;
            },
            stringContent: (obj: ServiceType) => obj.name,
        },
        {
            field: 'active',
            title: 'Active',
            sortable: true,
            filter: false,
            value: (row: ServiceType) => (row.active ? 'Yes' : 'No'),
            stringContent: (obj: ServiceType) => (obj.active ? 'Yes' : 'No'),
        },
    ];

    // render data table
    return <DataTableComp {...tablePropsProvider(APIPath.service.index())} columns={column} />;
};

export default ServiceListComp;
