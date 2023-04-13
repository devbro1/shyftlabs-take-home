import { APIPath, RoutePath } from 'data';
import React from 'react';
import { Link } from 'react-router-dom';
import { __AnnouncementListStyle as Styles } from './list.styles';
import { TranslationType } from 'types';
import { DataTableComp, tablePropsProvider } from 'helperComps';

// translations list page
const TranslationListComp: React.FC = () => {
    // data table component column prop
    const column = [
        {
            field: 'key',
            title: 'Original key',
            sortable: true,
            filter: true,
            value: (row: TranslationType) => (
                <Link className={Styles.link} to={RoutePath.translation.edit(row.id)}>
                    {row.key}
                </Link>
            ),
            stringContent: (obj: TranslationType) => obj.key,
        },
        {
            field: 'translation',
            title: 'Translation',
            sortable: true,
            filter: true,
            value: (row: TranslationType) => {
                return row.translation;
            },
            stringContent: (obj: TranslationType) => obj.translation,
        },
        {
            field: 'language',
            title: 'Language',
            sortable: true,
            filter: true,
            value: (row: TranslationType) => {
                return row.language;
            },
            stringContent: (obj: TranslationType) => obj.language,
        },
    ];

    return (
        <div className={Styles.root}>
            <DataTableComp {...tablePropsProvider(APIPath.translation.index())} columns={column} />
        </div>
    );
};

export default TranslationListComp;
