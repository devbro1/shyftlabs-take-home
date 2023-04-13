### TextEditorComp

this is the draft js text editor component witch support react-hook-form controller \

#### props:

-   control: (required) controller object of react-hook-form
-   name: (required) react-hook-form controller field name
-   title: (optional) input title
-   description: (optional) input description display blow the input
-   className: (optional) styling classes

#### notes:

-   Draft js return empty p tag after focus and make fake value for input \
    because of validating input value there is an regex in on change handler to prevent logic issue

-   draft js has temporary issue on this version, so default value is only available (not value)

#### TODO:

-   fix set value issue on draft

#### How to implement:

```javascript
import { TextEditorComp } from 'utils';

...

const validationSchema = yup.object().shape({
    body: yup.string().required(),
});

const { control } = useForm<FieldValues>({
    resolver: yupResolver(validationSchema),
    defaultValues: { body: '' },
});

...

return (
    <TextEditorComp className={Styles.fields} name="body" control={control} title="Body" />
);
```
