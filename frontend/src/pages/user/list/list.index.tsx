import React from 'react';
import { APIPath, RoutePath } from 'data';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { UserType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// user list page
const UserListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            title: 'User ID',
            field: 'id',
            value: (row: UserType) => (
                <Link className={Styles.link} to={RoutePath.user.edit(row.id)}>
                    {row.id}
                </Link>
            ),

            filter: true,
            className: 'w-12',
        },
        {
            title: 'Username',
            sortable: true,
            field: 'username',
            value: (row: UserType) => row.username,

            filter: true,
            className: 'w-48',
        },
        {
            title: 'Full Name',
            sortable: true,
            field: 'full_name',
            value: (row: UserType) => row.full_name,

            filter: true,
        },
        {
            title: 'Email',
            sortable: true,
            field: 'email',
            value: (row: UserType) => row.email,

            filter: true,
        },
    ];

    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.user.index())} columns={column} />
        </div>
    );
};

export default UserListComp;
