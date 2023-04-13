### FlowChart

this is the flow chart input component witch support react-hook-form controller \

#### props:

-   control: (required) controller object of react-hook-form
-   name: (required) react-hook-form controller field name
-   title: (optional) input title
-   description: (optional) input description display blow the input
-   className: (optional) styling classes

#### How to implement:

```javascript
import { FlowChartComp } from 'utils';

...

const validationSchema = yup.object().shape({
    flowchart: yup.string().required(),
});

const { handleSubmit, reset, control, getValues } = useForm<FieldValues>({
    resolver: yupResolver(validationSchema),
});

...

return (
    <FlowChartComp
        name="flowchart"
        control={control}
        title="Flow Chart"
    />
);
```
