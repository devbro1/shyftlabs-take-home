### Context

this is the type modules of the project\
you shouldn't put all types here, only reusable ones.

_for adding each new type please at first define it in suitable file and then reexport from index.ts file_
_some types some times have depended types, please just reexport reusable ones_

#### files:

-   index.ts: reexport others files reusable content

-   context.ts: global state related types

-   general.ts: types of reusable values independent of app specific models

-   models.ts: backend response types and logical value types.

#### How to use:

```javascript
import { AnyType } from 'types';
```
