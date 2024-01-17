import React from 'react';
import { FieldValues, useForm } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from 'yup';
import { __RestAPI as RestAPI } from 'scripts/api';
import { APIPath } from 'data';
import { toast } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
import { FormComp, TextInputComp } from 'utils';
import { __SignUpPageStyles as Styles } from './signUp.styles';

const SignUpComp: React.FC = () => {
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({
        full_name: yup.string().required(),
        username: yup.string().required(),
        email: yup.string().required().email(),
        password: yup.string().required(),
        password_confirmation: yup
            .string()
            .required()
            .oneOf([yup.ref('password')]),
    });

    const { handleSubmit, getValues, control } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: {},
    });

    function handleForm() {
        const data = { ...getValues() };

        RestAPI.post(APIPath.auth.signUp, data)
            .then(() => {
                toast.success('User was created successfully, You can login to your account now.');
                navigate(APIPath.auth.signIn);
            })
            .catch(() => {
                toast.error('Some thing went wrong. please try again later.');
            });
    }

    return (
        <div className={Styles.root}>
            <FormComp
                onSubmit={handleSubmit(handleForm)}
                title="Register New Account"
                className={Styles.form}
                buttonTitle="Sign up"
            >
                <TextInputComp
                    className={Styles.fields}
                    name="full_name"
                    control={control}
                    type="text"
                    title="Full Name"
                />
                <TextInputComp
                    className={Styles.fields}
                    name="username"
                    control={control}
                    type="text"
                    title="Username"
                />
                <TextInputComp className={Styles.fields} name="email" control={control} type="text" title="Email" />
                <TextInputComp
                    className={Styles.fields}
                    name="password"
                    control={control}
                    type="password"
                    title="Password"
                />
                <TextInputComp
                    className={Styles.fields}
                    name="password_confirmation"
                    control={control}
                    type="password"
                    title="Confirm password"
                />
            </FormComp>
        </div>
    );
};

export default SignUpComp;
