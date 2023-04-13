### Form

this is the form component witch controls form submission, form title and loading state of submit button \

#### props:

-   title: (optional) title text of form
-   buttonTitle: (optional) text title of submit button (default: Submit)
-   onSubmit: (optional) function witch call on form submit button click (after validation in forms with controller) \
    if you don't set this props your form render without submit button
    if your function returns Promise your button render with loading state
-   controllerSubmit: (optional) react-hook-form module handleSubmit function if you want to user form with controller
-   className: (optional) styling classes

#### How to implement:

```javascript
import { FormComp } from 'utils';

...

function handleForm() {
    return new Promise((resolve, reject) => {
        // do sth
        resolve(true)
    });
}

...

return (
    <FormComp onSubmit={handleForm} controllerSubmit={handleSubmit}>
        ...
    </FormComp>
);
```
