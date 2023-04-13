import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { StoreType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// store list page
const StoreListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            title: 'id',
            sortable: true,
            filter: true,
            field: 'ID',
            value: (row: StoreType) => (
                <Link className={Styles.link} to={RoutePath.store.edit(row.id)}>
                    {row.id}
                </Link>
            ),
            stringContent: (obj: StoreType) => obj.name,
        },
        {
            title: 'Name',
            sortable: true,
            filter: true,
            field: 'name',
            value: (row: StoreType) => {
                return row.name;
            },
            stringContent: (obj: StoreType) => obj.name,
        },
        {
            field: 'active',
            title: 'Active',
            sortable: true,
            filter: false,
            value: (row: StoreType) => (row.active ? 'Yes' : 'No'),
            stringContent: (obj: StoreType) => (obj.active ? 'Yes' : 'No'),
        },
    ];

    // render data table
    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.store.index())} columns={column} />
        </div>
    );
};

export default StoreListComp;
