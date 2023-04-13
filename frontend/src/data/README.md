### Data

these are the useful data in multiple components of project specially route paths and api urls \
api urls and route paths are can change easily from here, no need to change in router and redirects

#### files:

-   APIPath.ts: hole project api calls

-   routerPath.tsx: router paths of hole project to use them in router definition and redirect

-   index.ts: reexport reusable things of hole data folder.\
    so every where you can import them just from 'data' instead of 'data/sth.ext'

#### How to use:

```javascript
import { AnyData } from 'data';
```
