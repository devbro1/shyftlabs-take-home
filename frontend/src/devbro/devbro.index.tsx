import { GlobalContext } from 'context';
import { RoutePath } from 'data';
import { FullPageLoadingComp } from 'helperComps';
import DashboardLayoutComp from 'helperComps/dashboardLayout/dashboardLayout.index';
import React, { Suspense, useContext, useEffect } from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import { AuthStatusEnum } from 'types';
import ActionComp from './action/action.index';
import AnnouncementComp from './announcement/announcement.index';
import AuthComp from './auth/auth.index';
import { __checkUserAuthStatus } from './devbro.scripts';
import PermissionComp from './permission/permission.index';
import RoleComp from './role/role.index';
import ServiceComp from './service/service.index';
import StoreComp from './store/store.index';
import UserComp from './user/user.index';
import LeadComp from './lead/lead.index';
import CompanyComp from './company/company.index';
import WorkflowComp from './workflow/workflow.index';
import TranslationComp from './translation/translation.index';
import AppointmentComp from './appointment/appointment.index';
import Alerts from 'helperComps/Alert/Alerts';
import CustomerLeadFormComp from './lead/form/customer.form';
import { FaSpinner } from 'react-icons/fa';
import { Styles } from 'styles';

// higher level router & authorization permission controller
function DevbroComp() {
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
                            <CustomerLeadFormComp />
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
            <Alerts />
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
                    <Route path={RoutePath.store.__index + '/*'} element={<StoreComp />} />
                    <Route path={RoutePath.service.__index + '/*'} element={<ServiceComp />} />
                    <Route path={RoutePath.workflow.__index + '/*'} element={<WorkflowComp />} />
                    <Route path={RoutePath.action.__index + '/*'} element={<ActionComp />} />
                    <Route path={RoutePath.lead.__index + '/*'} element={<LeadComp />} />
                    <Route path={RoutePath.company.__index + '/*'} element={<CompanyComp />} />
                    <Route path={RoutePath.translation.__index + '/*'} element={<TranslationComp />} />
                    <Route path={RoutePath.appointment.__index + '/*'} element={<AppointmentComp />} />
                    <Route path="*" element={<Navigate to={RoutePath.announcement.__index} replace />} />
                </Routes>
            </Suspense>
        </DashboardLayoutComp>
    );
}

export default DevbroComp;
