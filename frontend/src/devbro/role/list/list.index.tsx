import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { RoleType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// roles list page
const RoleListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            title: 'Name',
            sortable: true,
            filter: true,
            field: 'name',
            value: (row: RoleType) => (
                <Link className={Styles.link} to={RoutePath.role.edit(row.id)}>
                    {row.name}
                </Link>
            ),
            stringContent: (obj: RoleType) => obj.name,
        },
        {
            field: 'description',
            title: 'Description',
            sortable: true,
            filter: true,
            value: (row: RoleType) => row.description,
            stringContent: (obj: RoleType) => obj.description,
        },
    ];

    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.role.index())} columns={column} />
        </div>
    );
};

export default RoleListComp;
