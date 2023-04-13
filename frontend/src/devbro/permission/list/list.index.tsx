import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { PermissionType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// permissions list page
const PermissionListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            field: 'name',
            title: 'name',
            sortable: true,
            filter: true,
            value: (row: PermissionType) => (
                <Link className={Styles.link} to={RoutePath.permission.edit(row.id)}>
                    {row.name}
                </Link>
            ),
            stringContent: (obj: PermissionType) => obj.name,
        },
    ];

    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.permission.index())} columns={column} />
        </div>
    );
};

export default PermissionListComp;
