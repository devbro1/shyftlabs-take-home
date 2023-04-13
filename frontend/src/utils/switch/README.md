### SwitchComp

this is the switch component which support react-hook-form controller \

#### props:

-   className: (optional) extra classes for styling \

-   control: (required) controller object of react-hook-form

-   name: (required) react-hook-form controller field name

-   title: (optional) input title

-   description: (optional) input description display blow the input

#### notes:

-   this component support error message (90% is useless)

#### How to implement:

```javascript
import { SwitchComp } from 'utils';

...

const validationSchema = yup.object().shape({
    active: yup.boolean().required(),
});

...

return (
    <SwitchComp name="active" control={control} title="Active" className={Styles.spacer} />
);
```
