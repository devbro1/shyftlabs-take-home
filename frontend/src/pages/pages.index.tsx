import { GlobalContext } from 'context';
import { RoutePath } from 'data';
import { FullPageLoadingComp } from 'helperComps';
import Alerts from 'helperComps/Alert/Alerts';
import { FaSpinner } from 'react-icons/fa';
import { Styles } from 'styles';
import DashboardLayoutComp from 'helperComps/dashboardLayout/dashboardLayout.index';
import React, { Suspense, useContext, useEffect } from 'react';
import { __checkUserAuthStatus } from './pages.scripts';
import { Navigate, Route, Routes } from 'react-router-dom';
import { AuthStatusEnum } from 'types';

const AnnouncementComp = React.lazy(() => import('./announcement/announcement.index'));
const AuthComp = React.lazy(() => import('./auth/auth.index'));
const PermissionComp = React.lazy(() => import('./permission/permission.index'));
const RoleComp = React.lazy(() => import('./role/role.index'));
const UserComp = React.lazy(() => import('./user/user.index'));
const TranslationComp = React.lazy(() => import('./translation/translation.index'));
const StudentComp = React.lazy(() => import('./student/student.index'));

// higher level router & authorization permission controller
function PagesComp() {
    const context = useContext(GlobalContext);

    useEffect(() => {
        // check if user is authorized or not and if he is, get his profile
        __checkUserAuthStatus(context);
    }, []);

    // show loading until authorization check end (calling /me api)
    if (context.authStatus === AuthStatusEnum.pending) return <FullPageLoadingComp />;

    // routes for unauthorized users
    if (context.authStatus === AuthStatusEnum.invalid) {
        return (
            <Routes>
                <Route path="/auth/*" element={<AuthComp />} />
                <Route
                    path="/customer-leads/*"
                    element={
                        <>
                            <Alerts />
                        </>
                    }
                />
                <Route path="*" element={<Navigate to="/auth/sign-in" replace />} />
            </Routes>
        );
    }

    // render main router base on user authentication status
    return (
        <DashboardLayoutComp>
            <Suspense
                fallback={
                    <div className={Styles.loading}>
                        <FaSpinner size={48} className={Styles.loadingIcon} />
                    </div>
                }
            >
                <Routes>
                    <Route path={RoutePath.announcement.__index + '/*'} element={<AnnouncementComp />} />
                    <Route path={RoutePath.user.__index + '/*'} element={<UserComp />} />
                    <Route path={RoutePath.role.__index + '/*'} element={<RoleComp />} />
                    <Route path={RoutePath.permission.__index + '/*'} element={<PermissionComp />} />
                    <Route path={RoutePath.translation.__index + '/*'} element={<TranslationComp />} />
                    <Route path={RoutePath.student.__index + '/*'} element={<StudentComp />} />
                    <Route path="*" element={<Navigate to={RoutePath.announcement.__index} replace />} />
                </Routes>
            </Suspense>
        </DashboardLayoutComp>
    );
}

export default PagesComp;
