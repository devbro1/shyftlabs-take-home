### ButtonComp

this is the pagination component which controls by states \
there is no internal state, it's just view.

#### props:

-   className: (optional) extra classes for styling \

-   onClick: (optional) on button click callback \
    if callback return promise loading status will activated

-   href: (optional) href string \
    if you use `href` prop you change your button to `<Link/>`

-   outOfRouter: (optional) if you use `href` prop and also use set this props `true` you changed your button `<a/>`
-   onPageSizeChange: (optional) on page size change
-   options: (optional) available options for page size select, default is in `pagination.data.ts` file

#### notes:

-   this component also support link and a tag

-   this component support loading if your callback returns promise

#### How to implement:

```javascript
import { ButtonComp } from 'utils';

...

function onClickHandler(){
    return new Promise((resolve) => {
        // do some thing
        resolve(true)
    })
}

...

return (
    <ButtonComp onClick={onClickHandler}>Download PDF</ButtonComp> // with loading
    <ButtonComp href="/announcement">Download PDF</ButtonComp> // <Link> component
    <ButtonComp href="/announcement" outOfRouter={true}>Download PDF</ButtonComp> // <a> tag
);
```
