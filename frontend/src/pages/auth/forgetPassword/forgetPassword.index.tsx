import React, { useState } from 'react';
import * as yup from 'yup';
import { __RestAPI as RestAPI } from 'scripts/api';
import { APIPath } from 'data';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { toast } from 'react-toastify';
import { __ForgetPasswordPageStyles as Styles } from './forgetPassword.styles';
import { FormComp, TextInputComp } from 'utils';

const ForgetPasswordComp: React.FC = () => {
    const [success, setSuccess] = useState<boolean>(false);
    const validationSchema = yup.object().shape({
        email: yup.string().required().email(),
    });

    const { handleSubmit, reset, control, getValues } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: { email: '' },
    });

    function handleForm() {
        const data = { ...getValues() };
        return new Promise((resolve) => {
            RestAPI.post(APIPath.auth.forgetPassword, data)
                .then(() => {
                    setSuccess(true);
                })
                .catch(({ status }) => {
                    // error handling
                    if (status >= 500) {
                        toast.error('Could not connect, please try again later');
                    } else if (status >= 400) {
                        toast.error('Unable to send reset link');
                        reset();
                    } else {
                        toast.error('Unknown error');
                    }
                })
                .finally(() => {
                    resolve(true);
                });
        });
    }

    return (
        <div className={Styles.root}>
            {success ? (
                <div className={Styles.form}>
                    <p className={Styles.message}>Reset link sent to your email.</p>
                </div>
            ) : (
                <FormComp
                    onSubmit={handleSubmit(handleForm)}
                    title="Password Request Form"
                    buttonTitle="Request Password Reset"
                    className={Styles.form}
                >
                    <TextInputComp className={Styles.fields} name="email" control={control} type="text" title="Email" />
                </FormComp>
            )}
        </div>
    );
};

export default ForgetPasswordComp;
