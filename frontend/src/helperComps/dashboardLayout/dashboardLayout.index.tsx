import React from 'react';
import FooterComp from './footer/footer.index';
import HeaderComp from './header/header.index';
import SideNavComp from './sideNav/sideNav.index';
import { __DashboardLayoutStyles as Styles } from './dashboardLayout.styles';

const DashboardLayoutComp: React.FC<{ children: React.ReactNode }> = (props: { children: React.ReactNode }) => {
    return (
        <div className={Styles.root}>
            <div className={Styles.main}>
                <SideNavComp />
                <div className={Styles.content}>
                    <HeaderComp />
                    <main className={Styles.contentBody}>{props.children}</main>
                </div>
            </div>
            <FooterComp />
        </div>
    );
};

export default DashboardLayoutComp;
