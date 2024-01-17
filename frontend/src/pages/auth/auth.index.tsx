import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import ForgetPasswordComp from './forgetPassword/forgetPassword.index';
import ResetPasswordComp from './resetPassword/resetPassword.index';
import SignInComp from './signIn/signIn.index';
import SignUpComp from './signUp/signUp.index';
import VerifyEmailComp from './verifyEmail/verifyEmail.index';

// authentication pages router
const AuthComp: React.FC = () => {
    return (
        <Routes>
            <Route path="sign-in" element={<SignInComp />} />
            <Route path="sign-up" element={<SignUpComp />} />
            <Route path="forgot-password" element={<ForgetPasswordComp />} />
            <Route path="reset-password/:token" element={<ResetPasswordComp />} />
            <Route path="verify-email/:id/:token" element={<VerifyEmailComp />} />
            <Route path="*" element={<Navigate to="/auth/sign-in" />} />
        </Routes>
    );
};

export default AuthComp;
