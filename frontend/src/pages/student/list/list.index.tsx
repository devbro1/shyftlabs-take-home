import React, { useState } from 'react';
import { APIPath, RoutePath } from 'data';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { StudentType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';
import { Popover } from '@headlessui/react';
import { DateRangePicker } from 'react-date-range';
import 'react-date-range/dist/styles.css';
import 'react-date-range/dist/theme/default.css';


const StudentListComp: React.FC = () => {
    const [dateRange, setDateRange] = useState('2020-1-1');

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

            filter: (opts: any) => {
                const dates = dateRange.split(',');
                const selectionRange = {
                    startDate: new Date(dates[0]),
                    endDate: new Date(dates[1]),
                    key: 'selection',
                };

                function dateChange(value: any) {
                    const start = value.selection.startDate.toISOString().substring(0, 10);
                    const end = value.selection.endDate.toISOString().substring(0, 10);
                    setDateRange(start + ',' + end);
                    opts.setValue('date_of_birth', start + ',' + end);
                }
                return (
                    <Popover>
                        <Popover.Button className="align-left">
                            <input
                                type="text"
                                className={opts.Styles.headerSearch}
                                value={opts.getValues('date_of_birth')}
                            />
                        </Popover.Button>
                        <Popover.Panel>
                            <div
                                className={'absolute border border-indigo-600 z-20 bg-white top-0 right-0'}
                                style={{ zIndex: '9999' }}
                            >
                                <DateRangePicker ranges={[selectionRange]} onChange={dateChange} />
                            </div>
                        </Popover.Panel>
                    </Popover>
                );
            },
        },
    ];

    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.student.index())} columns={column} />
        </div>
    );
};

export default StudentListComp;
