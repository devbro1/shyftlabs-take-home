### Context

this is the global state of hole project that keeps the common variables (in components) of project as a state\
the architecture of update index is base on flux architecture.

_don't touch the container.tsx and index.ts files_

_only value.ts and actions.ts expands by project expansion_

#### files:

-   value.ts: initial value of global state (on page load)

-   container.tsx: provider component and update function logic

-   actions.ts: helper function for setting new value to global state

-   index.ts: reexport reusable things of hole context folder.\
    so every where you can import them just from 'context' instead of 'context/sth.ext'

#### How to use:

in root element:

```javascript
import { ContextProviderComp } from 'context';
...

function Comp() {
    return (
        <ContextProviderComp>
            ...
        </ContextProviderComp>
    );
}
```

in consumer components:

```javascript
import { GlobalContext } from 'context';
import React, { useContext } from 'react';
...

// to access to state
const context = useContext(GlobalContext);

//to update values
context.update(
    {key, value},
    {key, value}
)

// to listen to changes
useEffect(() => {
    // do sth
}, [context.field]);

```
