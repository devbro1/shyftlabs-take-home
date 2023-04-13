### DataTableComp

this is data table with search & sort & pdf generator & optional api call handler

#### Notes:

-   this component has 2 type of different implementation: 1-data base 2-url base

#### props base on data:

-   columns: (required) columns array prop which handle search, filter, title, etc. [Read more here](#column-object)

-   data: (technically optional but required for functionality) list of rows in data table. /
    empty array cause empty state and undefined cause loading status

-   page: (optional) page number (default: 1)

-   onPageChange: (optional) function to handle page change by pagination and get next page data

-   pageSize: (optional) number of records in each page (default: 10)

-   onPageSizeChange: (optional) function to handle page size change by pagination and get new page data

#### props base on url:

-   columns: (required) columns array prop which handle search, filter, title, etc. [Read more here](#column-object)

-   url: (technically optional but required for functionality) url for api call

#### column object:

-   title: (required) text title that you want to display on table header

-   sortable: (required) boolean value to determine weather this column is sortable or not.

-   value: (required) function that return element or string in the column

-   stringContent: (required) string value of each row for this column for search functionality

#### How to implement base on url:

```javascript
import { DataTableComp } from 'helperComps';

...

const column = [
    {
        title: 'title',
        sortable: true,
        value: (row: AnyObj) => (
            <Link className={Styles.link} to={`/sth/${row.id}`}>
                {row.name}
            </Link>
        ),
        stringContent: (obj: AnyObj) => obj.name,
    },
];

...

return <DataTableComp url={'my-url'} columns={column} />;
```
