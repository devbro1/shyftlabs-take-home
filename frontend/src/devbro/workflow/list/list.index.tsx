import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { WorkflowType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// roles list page
const WorkflowListComp: React.FC = () => {
    const columns = [
        {
            field: 'id',
            title: 'ID',
            sortable: true,
            filter: true,
            value: (row: WorkflowType) => (
                <Link className={Styles.link} to={RoutePath.workflow.edit(row.id)}>
                    {row.id}
                </Link>
            ),
            stringContent: (obj: WorkflowType) => obj.id.toString(),
        },
        {
            field: 'name',
            title: 'Name',
            sortable: true,
            filter: true,
            value: (row: WorkflowType) => row.name,
            stringContent: (obj: WorkflowType) => obj.name,
        },
    ];

    // render data table
    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.workflow.index())} columns={columns} />
        </div>
    );
};

export default WorkflowListComp;
