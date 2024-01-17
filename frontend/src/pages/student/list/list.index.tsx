import React from 'react';
import { APIPath, RoutePath } from 'data';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { StudentType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

const StudentListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            title: 'Student ID',
            field: 'id',
            value: (row: StudentType) => (
                <Link className={Styles.link} to={RoutePath.student.edit(row.id)}>
                    {row.id}
                </Link>
            ),
            className: 'w-48',
            filter: true,
            
        },
        {
            title: 'First Name',
            sortable: true,
            field: 'first_name',
            value: (row: StudentType) => row.first_name,

            filter: true,
            className: 'w-60',
        },
        {
            title: 'Family Name',
            sortable: true,
            field: 'family_name',
            value: (row: StudentType) => row.family_name,
            className: 'w-60',

            filter: true,
        },
        {
            title: 'Email',
            sortable: true,
            field: 'email',
            value: (row: StudentType) => row.email,
            className: 'w-60',
            filter: true,
        },
        {
            title: 'Date of Birth',
            sortable: true,
            field: 'date_of_birth',
            value: (row: StudentType) => row.date_of_birth,

            filter: true,
        },
    ];

    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.student.index())} columns={column} />
        </div>
    );
};

export default StudentListComp;
