import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { ActionType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// actions list page
const ActionListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            field: 'id',
            title: 'ID',
            sortable: true,
            filter: true,
            value: (row: ActionType) => (
                <Link className={Styles.link} to={RoutePath.action.edit(row.id)}>
                    {row.id}
                </Link>
            ),
            stringContent: (obj: ActionType) => obj.name,
        },
        {
            field: 'name',
            title: 'Name',
            sortable: true,
            filter: true,
            value: (row: ActionType) => row.name,
            stringContent: (obj: ActionType) => obj.name,
        },
    ];

    // render data table
    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.action.index())} columns={column} />
        </div>
    );
};

export default ActionListComp;
