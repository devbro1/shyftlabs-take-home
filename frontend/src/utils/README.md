### Utils

these are the useful components in multiple components of project \
they aren't pages they are UIKit components, sth like specific inputs or buttons

#### Components:

-   form: form component to add submit button and form functionalities

-   textInput: simple text input with type prop to render and control kids of inputs like text, password, number, etc.

-   input: gather all inputs in one wrapper component /
    we don't recommend to use it instead of exact component because of code readability).

-   index.ts: reexport reusable things of hole utils folder.\
    so every where you can import them just from 'utils' instead of 'utils/sth.ext'

```javascript
import { TextInputComp, InputComp, FormComp } from 'utils';
```
