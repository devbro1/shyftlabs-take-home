import React, { useContext } from 'react';
import FooterComp from './footer/footer.index';
import HeaderComp from './header/header.index';
import SideNavComp from './sideNav/sideNav.index';
import { __DashboardLayoutStyles as Styles } from './dashboardLayout.styles';
import { GlobalContext } from 'context';
import { AppContextActionKeyEnum } from 'types';
import Alerts from 'helperComps/Alert/Alerts';

function DashboardLayoutComp(props: { children: React.ReactNode }) {
    const context = useContext(GlobalContext);
    return (
        <>
            <nav className="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <HeaderComp />
            </nav>
            <div className="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
                <SideNavComp />
                <div
                    className={
                        'fixed inset-0 z-10 bg-gray-900/50 dark:bg-gray-900/90 ' + (context.showSideBar ? '' : 'hidden')
                    }
                    onClick={() => {
                        context.update({ key: AppContextActionKeyEnum.showSideBar, value: false });
                    }}
                    id="sidebarBackdrop"
                ></div>
                <div
                    id="main-content"
                    className="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-0 dark:bg-gray-900"
                >
                    <main className="text-gray-600 dark:text-gray-400">
                        <Alerts />
                        <div className="my-6 mx-4 bg-white shadow-sm border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            {props.children}
                        </div>
                    </main>
                    <footer className="p-4 my-6 mx-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 xl:p-8 dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                        <FooterComp />
                    </footer>
                </div>
            </div>
        </>
    );
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
}

export default DashboardLayoutComp;
