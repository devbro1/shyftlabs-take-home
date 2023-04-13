import React, { useEffect, useState } from 'react';
import { RestAPI } from 'scripts';
import { APIPath } from 'data';
import { useNavigate, useParams } from 'react-router-dom';
import { __VerifyEmailPageStyles as Styles } from './VerifyEmail.styles';
import { useDelayFunction } from 'delay-function-hook/lib';

const VerifyEmailComp: React.FC = () => {
    const navigate = useNavigate();
    const params = useParams<any>();
    const [message, setMessage] = useState('');
    const delay = useDelayFunction();

    useEffect(() => {
        RestAPI.get(APIPath.auth.verifyEmail(params.id || '', params.token || '')).then((response: any) => {
            setMessage(response.data.message);

            delay(() => {
                setTimeout(() => {
                    navigate('/');
                }, 5000);
            }, 1500);
        });
    }, []);

    return (
        <div className={Styles.root}>
            <div className={Styles.form}>{message}</div>
        </div>
    );
};

export default VerifyEmailComp;
