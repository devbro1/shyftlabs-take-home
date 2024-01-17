import React from 'react';
import { APIPath } from 'data';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { ResultType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';
import 'react-date-range/dist/styles.css';
import 'react-date-range/dist/theme/default.css';

const ResultListComp: React.FC = () => {

    const column = [
        {
            title: 'Student',
            field: 'student',
            value: (row: ResultType) => {
                return row.student.first_name + ' ' + row.student.family_name;
            },
            className: 'w-48',
            filter: true,
        },
        {
            title: 'Course',
            sortable: true,
            field: 'course',
            value: (row: ResultType) => row.course.name,

            filter: true,
            className: 'w-60',
        },
        {
            title: 'Score',
            sortable: true,
            field: 'score',
            value: (row: ResultType) => row.score,
            className: 'w-12',

            filter: true,
        },
    ];

    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.result.index())} columns={column} />
        </div>
    );
};

export default ResultListComp;
