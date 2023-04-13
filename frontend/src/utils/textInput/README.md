### TextInput

this is the simple text input component witch support react-hook-form controller \

#### props:

-   control: (required) controller object of react-hook-form
-   name: (required) react-hook-form controller field name
-   title: (optional) input title
-   description: (optional) input description display blow the input
-   className: (optional) styling classes
-   type: (optional) input type (default: text)
-   placeholder: (optional) input placeholder

#### How to implement:

```javascript
import { TextInputComp } from 'utils';

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
    <TextInputComp
        name="username"
        control={control}
        type="text"
        title="Email Address"
        placeholder="johndoe@gmail.com"
    />
);
```
