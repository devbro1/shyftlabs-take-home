### PaginationComp

this is the pagination component which controls by states \
there is no internal state, it's just view.

#### props:

-   page: (required) current page number \
    technically it's optional but it's required to render component otherwise will render nothing
-   total: (required) number of total records \
    technically it's optional but it's required to render component otherwise will render nothing
-   pageSize: (required) number of each page records \
    technically it's optional but it's required to render component otherwise will render nothing \
    initial value must be one of available options otherwise set your custom options in `options` prop
-   onChange: (optional) on page change callback
-   onPageSizeChange: (optional) on page size change
-   options: (optional) available options for page size select, default is in `pagination.data.ts` file

#### notes:

-   this pagination component is base on 7 page button

-   the page buttons series generation algorithm is base on readability not efficiency (BTW it's efficient)

#### TODO:

-   add page size default value to page size option list automatically to prevent implementation logic issues

-   buttons series generation algorithm to dynamic one /
    so we could determine number of page buttons

#### How to implement:

```javascript
import { PaginationComp } from 'utils';

...

const [page, setPage] = useState<number>(1);
const [pageSize, setPageSize] = useState<number>(10);
const [data, setData] = useState<T[]>([]);

...

return (
    <PaginationComp
        page={page}
        pageSize={pageSize}
        total={data.length}
        onPageSizeChange={setPageSize}
        onChange={setPage}
    />
);
```
