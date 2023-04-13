import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __ListStyle as Styles } from './list.styles';
import { CompanyType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// services list page
const CompanyListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            title: 'id',
            sortable: true,
            filter: true,
            field: 'ID',
            value: (row: CompanyType) => (
                <Link className={Styles.link} to={RoutePath.company.edit(row.id)}>
                    {row.id}
                </Link>
            ),
            stringContent: (obj: CompanyType) => obj.name,
        },
        {
            title: 'Name',
            sortable: true,
            filter: true,
            field: 'name',
            value: (row: CompanyType) => {
                return row.name;
            },
            stringContent: (obj: CompanyType) => obj.name,
        },
        {
            field: 'active',
            title: 'Active',
            sortable: true,
            filter: false,
            value: (row: CompanyType) => (row.active ? 'Yes' : 'No'),
            stringContent: (obj: CompanyType) => (obj.active ? 'Yes' : 'No'),
        },
    ];

    // render data table
    return <DataTableComp {...tablePropsProvider(APIPath.company.index())} columns={column} />;
};

export default CompanyListComp;
