import React from 'react';
import { Link } from 'react-router-dom';
import { useState, useEffect } from 'react';
import { useContext } from 'react';
import * as yup from 'yup';
import { GlobalContext } from 'context';
import { CookiesInterface, RestAPI } from 'scripts';
import { APIPath, RoutePath } from 'data';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { __SingInAPIResponse } from './signIn.types';
import { AppContextActionKeyEnum, AuthStatusEnum } from 'types';
import { toast } from 'react-toastify';
import { __SignInPageStyles as Styles } from './signIn.styles';
import { FormComp, TextInputComp } from 'utils';
import _ from 'lodash';

const SignInComp: React.FC = () => {
    // const [client_secret, setClientSecret] = useState(''); // TODO: Check functionality
    const context = useContext(GlobalContext);
    // background image url of left part of right
    const [imageUrl, setImageUrl] = useState();

    const validationSchema = yup.object().shape({
        username: yup.string().required(),
        password: yup.string().required(),
    });

    const { handleSubmit, reset, control, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: { username: '', password: '' },
    });

    useEffect(() => {
        // get background image form unsplash
        RestAPI.get<any>(APIPath.others.unsplashRandom).then((res) => {
            setImageUrl(res.data?.urls?.regular);
        });
        reset();
        // TODO: Check functionality
        //     api.get('/tokens/secret').then((r) => {
        //         setClientSecret(r.data.secret);
        //     });
    }, []);

    function handleForm() {
        const data = { ...getValues() };
        data.scope = '*';
        data.client_id = 2;
        // data.client_secret = client_secret; // TODO: Check functionality
        data.grant_type = 'password';
        RestAPI.post<__SingInAPIResponse>(APIPath.auth.signIn, data)
            .then(({ data }) => {
                // set token to cookie for all apis authorization
                CookiesInterface.setAuth(data.access_token);
                // set user and authentication status after login
                context.update(
                    { key: AppContextActionKeyEnum.user, value: data.user },
                    { key: AppContextActionKeyEnum.authStatus, value: AuthStatusEnum.valid },
                    { key: AppContextActionKeyEnum.accessToken, value: data.access_token },
                );
            })
            .catch((error) => {
                toast.error(error.response.data.message);
                _.forEach(error.response.data.errors, (value, key) => {
                    setError(key, {
                        message: RestAPI.getErrorMessage('', Object.keys(value)[0], Object.values(value)[0]),
                    });
                });
            });
    }

    return (
        <div className={Styles.root}>
            <div className={Styles.screen}>
                {imageUrl ? <img src={imageUrl} alt="Background image" className={Styles.image} /> : null}
            </div>
            <div className={Styles.content}>
                <div className={Styles.formKeeper}>
                    <h1 className={Styles.title}>Log in to your account</h1>
                    <FormComp className={Styles.form} onSubmit={handleSubmit(handleForm)} buttonTitle="Sign In">
                        <TextInputComp
                            name="username"
                            control={control}
                            type="text"
                            title="Email Address"
                            placeholder="johndoe@gmail.com"
                        />
                        <TextInputComp
                            className={Styles.password}
                            name="password"
                            control={control}
                            type="password"
                            title="Password"
                            placeholder="Password"
                        />

                        <Link to={RoutePath.auth.forgetPassword()} className={Styles.forgetPassword}>
                            Forgot Password?
                        </Link>
                    </FormComp>
                    <hr className={Styles.separator} />
                    <p className={Styles.signUp}>
                        Need an account?
                        <Link to={RoutePath.auth.signUp()} className={Styles.signUpLink}>
                            Create an account
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default SignInComp;
