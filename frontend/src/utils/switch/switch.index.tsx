import { Switch } from '@headlessui/react';
import React from 'react';
import { Controller } from 'react-hook-form';
import { __SwitchStyles as Styles } from './switch.styles';
import { __SwitchProps } from './switch.types';

// switch component compatible with controller logic
const __SwitchComp: React.FC<__SwitchProps> = (props: __SwitchProps) => {
    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => (
                <div className={props.className}>
                    <Switch.Group>
                        <div className={Styles.root}>
                            {/* input optional title */}
                            {props.title ? (
                                <Switch.Label className={Styles.title(fieldState.invalid)}>{props.title}</Switch.Label>
                            ) : null}
                            {/* switch main body */}
                            <Switch
                                checked={field.value}
                                onChange={field.onChange}
                                onBlur={field.onBlur}
                                name={field.name}
                                className={Styles.body(Boolean(field.value))}
                            >
                                <span className={Styles.toggle(Boolean(field.value))} />
                            </Switch>
                        </div>
                        {/* switch error message */}
                        {fieldState.invalid && fieldState.error?.message ? (
                            <span className={Styles.error}>{fieldState.error?.message}</span>
                        ) : null}
                        {/* input optional description */}
                        {props.description ? <span className={Styles.description}>{props.description}</span> : null}
                    </Switch.Group>
                </div>
            )}
        />
    );
};

export default __SwitchComp;
