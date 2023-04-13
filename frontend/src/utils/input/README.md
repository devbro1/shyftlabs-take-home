### Input

this is the all inputs wrapper \
to more info visit each component README.md

#### props:

-   inputType: (required) type of input you want
-   ... (target input props)

#### How to implement:

```javascript
import { InputComp } from 'utils';

...

const validationSchema = yup.object().shape({
    username: yup.string().required(),
});

const { handleSubmit, reset, control, getValues } = useForm<FieldValues>({
    resolver: yupResolver(validationSchema),
    defaultValues: { username: '' },
});

...

return (
    <Input
        inputType="simple"
        name="username"
        control={control}
        type="text"
        title="Email Address"
        placeholder="johndoe@gmail.com"
    />
);
```
