### TextInput

this is the multi select input component witch support react-hook-form controller \

#### props:

-   control: (required) controller object of react-hook-form
-   name: (required) react-hook-form controller field name
-   title: (optional) input title
-   description: (optional) input description display blow the input
-   className: (optional) styling classes
-   options: (required) technically this field is optional but is required for functionality \
    should be list of object (type of MultiSelectOptionType)

#### TODO:

-   add placeholder

-   focus issue

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
    <MultiSelect
        options={data?.available_permissions.map((i) => ({ title: i.name, value: i.id }))}
        className={Styles.fields(true)}
        name="permissions"
        control={control}
        title="User Permissions"
    />
);
```
