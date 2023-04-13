import React from 'react';
import * as yup from 'yup';
import { RestAPI } from 'scripts';
import { APIPath, RoutePath } from 'data';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { toast } from 'react-toastify';
import { __ForgetPasswordPageStyles as Styles } from './resetPassword.styles';
import { FormComp, TextInputComp } from 'utils';
import { useNavigate, useParams } from 'react-router-dom';

const ResetPasswordComp: React.FC = () => {
    const { token } = useParams<any>();
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({
        email: yup.string().required().email(),
        password: yup.string().required(),
        password_confirmation: yup
            .string()
            .required()
            .oneOf([yup.ref('password')]),
    });

    const { handleSubmit, reset, control, getValues } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: { email: '', password: '', password_confirmation: '' },
    });

    function handleForm() {
        const data = { ...getValues() };
        const payload = {
            email: data.email,
            password: data.password,
            password_confirmation: data.password_confirmation,
            token,
        };
        return new Promise((resolve) => {
            RestAPI.post(APIPath.auth.resetPassword, payload)
                .then(() => {
                    toast.success('Password was resetted successfully');
                    navigate(RoutePath.auth.signIn());
                })
                .catch(({ status }) => {
                    // error handling
                    if (status >= 500) {
                        toast.error('Could not connect, please try again later');
                    } else if (status >= 400) {
                        toast.error('Unable to reset password');
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
            <FormComp
                onSubmit={handleSubmit(handleForm)}
                title="Password Request Form"
                buttonTitle="Reset Password"
                className={Styles.form}
            >
                <TextInputComp className={Styles.fields} name="email" control={control} type="text" title="Email" />
                <TextInputComp
                    className={Styles.fields}
                    name="password"
                    control={control}
                    type="password"
                    title="New Password"
                />
                <TextInputComp
                    className={Styles.fields}
                    name="password_confirmation"
                    control={control}
                    type="password"
                    title="Repeat Password"
                />
            </FormComp>
        </div>
    );
};

export default ResetPasswordComp;
