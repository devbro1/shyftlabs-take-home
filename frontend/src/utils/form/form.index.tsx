import React from 'react';
import { FaSpinner } from 'react-icons/fa';
import { FormStyles as Styles } from './form.styles';
import { __FormProps } from './form.types';

// form component for render submit button and on submit loading
const __FormComp: React.FC<__FormProps> = (props: __FormProps) => {
    // loading state of submit button controller
    // const [loading, setLoading] = useState<boolean>(false);

    // // on click to handle loading state of button
    // function innerOnClickHandler() {
    //     if (props.onSubmit) {
    //         const promise = props.onSubmit();
    //         // if onClick function return promise we should render loading, because the task is async
    //         if (promise) {
    //             setLoading(true);
    //             promise.then(() => {
    //                 setLoading(false);
    //             });
    //         }
    //     }
    // }

    // // support both controller and simple state of form
    // let onClick = innerOnClickHandler;
    // if (props.controllerSubmit) {
    //     onClick = props.controllerSubmit(innerOnClickHandler);
    // }

    return (
        <form noValidate={true} className={props.className} onSubmit={props.onSubmit}>
            {/* form optional title */}
            {props.title ? <h2 className={Styles.title}>{props.title}</h2> : null}
            {/* form content */}
            {props.children}
            {/* form optional submit button (base on onSubmit props) */}
            {props.buttonTitle ? (
                <div className="pt-2">
                    <input
                        disabled={props.isLoading}
                        type="submit"
                        name="submit"
                        className={Styles.submitButton}
                        value={props.buttonTitle ? props.buttonTitle : 'Submit'}
                    />
                    {/* loading spinner on loading state */}
                    {props.isLoading ? <FaSpinner className={Styles.spinner} /> : null}
                </div>
            ) : null}
        </form>
    );
};

__FormComp.defaultProps = {
    onSubmit: () => {
        return;
    },
};

export default __FormComp;
