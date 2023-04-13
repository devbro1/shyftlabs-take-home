import React, { useState } from 'react';
import { FaSort, FaSortDown, FaSortUp, FaSpinner } from 'react-icons/fa';
import { __DataTableProps, __DataTableColumnType } from './dataTable.types';
import { __FullPageLoadingStyles as Styles } from './dataTable.styles';
import { ButtonComp, PaginationComp } from 'utils';
import { __tablePDFGenerator } from './dataTable.scripts';
import { useForm } from 'react-hook-form';

// Data table component for list rendering
function __DataTableComp(props: __DataTableProps) {
    const [sortField, setSortField] = useState<string>('');
    const [sortDirection, setSortDirection] = useState<string>('');
    const { register, watch } = useForm({});

    React.useEffect(() => {
        const subscription = watch((value, {}) => onFilterChange(value));
        return () => subscription.unsubscribe();
    }, [watch]);

    // pdf file download handler
    function downloadPDF() {
        // if implementer component set url, get all data by api call (url base, not data base)
        return new Promise(() => {
            __tablePDFGenerator(props.data, props.columns);
        });
    }

    function onSortChange(field: string) {
        let dir = '';
        if (sortField !== field) {
            setSortField(field);
            setSortDirection('');
        } else if (sortDirection === '') {
            setSortDirection('-');
            dir = '-';
        } else {
            setSortDirection('');
        }

        props.onChange({ orderby: field, direction: dir });
    }

    function onFilterChange(e: any) {
        console.log(e);
        props.onChange({ filters: e });
    }

    function onPaginationChange(changes: any) {
        props.onChange({ page: 1, pageSize: changes });
    }

    function onPageChange(changes: any) {
        props.onChange({ page: changes });
    }

    let tableData: any = null;
    if (props.isLoading) {
        tableData = (
            <tr>
                <td colSpan={props.columns.length}>
                    <div className={Styles.loading}>
                        <FaSpinner size={48} className={Styles.loadingIcon} />
                    </div>
                </td>
            </tr>
        );
    } else if (props.data && props.data.length) {
        tableData = (
            <>
                {props.data.map((data, i) => (
                    <tr key={i}>
                        {props.columns.map((col, j) => (
                            <td key={j}>{col.value(data)}</td>
                        ))}
                    </tr>
                ))}
            </>
        );
    } else {
        tableData = (
            <tr>
                <td colSpan={props.columns.length}>
                    <p className={Styles.emptyState}>There is no data!</p>
                </td>
            </tr>
        );
    }

    return (
        <div className={Styles.root}>
            <div className={Styles.scrollKeeper}>
                <table className={Styles.body}>
                    <thead>
                        {/* header */}
                        <tr>
                            {props.columns.map((col, index) => {
                                let sort = null;
                                if ('sortable' in col && !col.sortable) {
                                    //do nothing
                                } else if (props.sort !== col.field) {
                                    sort = <FaSort />;
                                } else if (props.sortDirection === '') {
                                    sort = <FaSortDown />;
                                } else if (props.sortDirection === '-') {
                                    sort = <FaSortUp />;
                                }

                                let filter = null;
                                if (col.filter) {
                                    filter = (
                                        <div className="filter-container">
                                            <input
                                                {...register(col.field)}
                                                type="text"
                                                className={Styles.headerSearch}
                                            />
                                        </div>
                                    );
                                }

                                return (
                                    <th key={index}>
                                        {/* header title & sort sign */}
                                        <div
                                            onClick={() => {
                                                if (!('sortable' in col) || col.sortable) onSortChange(col.field);
                                            }}
                                            className={Styles.headerTitle}
                                        >
                                            <span>{col.title}</span>
                                            {sort}
                                        </div>
                                        {/* header title & sort sign */}
                                        {filter}
                                    </th>
                                );
                            })}
                        </tr>
                    </thead>
                    <tbody>{tableData}</tbody>
                </table>
            </div>
            <div className={Styles.footer}>
                <PaginationComp
                    page={props.page}
                    pageSize={props.pageSize}
                    total={props.total}
                    onChange={onPageChange}
                    onPageSizeChange={onPaginationChange}
                    options={props.paginationSizes}
                />
                <ButtonComp onClick={downloadPDF}>Download PDF</ButtonComp>
            </div>
        </div>
    );
}

__DataTableComp.defaultProps = {
    value: (row: any, column: __DataTableColumnType) => {
        return row[column.field || 0];
    },
    paginationSizes: [
        { title: '10', value: 10 },
        { title: '20', value: 20 },
        { title: '50', value: 50 },
        { title: '100', value: 100 },
        { title: '1000', value: 1000 },
    ],
    onChange: () => {
        return;
    },
    page: 1,
    pageSize: 10,
    pageCount: 1,
    isLoading: false,
};

export default __DataTableComp;
