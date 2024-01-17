import React from 'react';
import { APIPath, RoutePath } from 'data';
import { __RestAPI as RestAPI } from 'scripts/api';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { CourseType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';
import { IoClose } from 'react-icons/io5';
import { useQueryClient } from '@tanstack/react-query';

const StudentListComp: React.FC = () => {
    const queryClient = useQueryClient();

    function deleteCourse(course: CourseType) {
        if (confirm('Are you sure you want to delete ' + course.name + ' ?')) {
            RestAPI.delete(APIPath.course.index(course.id)).then(() => {
                queryClient.invalidateQueries({
                    queryKey: [APIPath.course.index()],
                    exact: false,
                    type: 'all',
                    refetchType: 'active',
                });
            });
        }
    }

    const column = [
        {
            title: 'Course ID',
            field: 'id',
            value: (row: CourseType) => (
                <Link className={Styles.link} to={RoutePath.course.edit(row.id)}>
                    {row.id}
                </Link>
            ),
            className: 'w-48',
            filter: true,
        },
        {
            title: 'Name',
            sortable: true,
            field: 'name',
            value: (row: CourseType) => row.name,

            filter: true,
            className: 'w-60',
        },
        {
            title: 'Delete',
            sortable: false,
            field: 'email',
            value: (row: CourseType) => {
                return (
                    <IoClose
                        color="red"
                        onClick={() => {
                            deleteCourse(row);
                        }}
                    />
                );
            },
            className: 'w-12',
            filter: false,
        },
    ];

    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.course.index())} columns={column} />
        </div>
    );
};

export default StudentListComp;
