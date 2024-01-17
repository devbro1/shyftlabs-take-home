import React, { useContext } from 'react';
import { IoClose, IoMoon, IoSunny } from 'react-icons/io5';
import { Popover } from '@headlessui/react';
import { GlobalContext } from 'context';
import { AppContextActionKeyEnum, AuthStatusEnum } from 'types';
import { CookiesInterface, RestAPI } from 'scripts';
import { APIPath } from 'data';
import { toast } from 'react-toastify';
import { BsPersonCircle } from 'react-icons/bs';
import { GiHamburgerMenu } from 'react-icons/gi';
import RefineLogo from './refineLogo';
import { useTranslation } from 'react-i18next';

// header component of dashboard base layout
const HeaderComp: React.FC = () => {
    const context = useContext(GlobalContext);
    const { t, i18n } = useTranslation();

    // app logout functionality
    function logout() {
        // first of all, show loading during the api call
        context.update({ key: AppContextActionKeyEnum.authStatus, value: AuthStatusEnum.pending });
        // now call logout api to expire your authorization data
        RestAPI.get(APIPath.auth.logout)
            .then(() => {
                // remove token from cookies
                CookiesInterface.logout();
                // remove user data from global state and update user authentication status in global state
                context.update(
                    { key: AppContextActionKeyEnum.user, value: null },
                    { key: AppContextActionKeyEnum.authStatus, value: AuthStatusEnum.invalid },
                );
            })
            .catch(() => {
                toast.error('Some thing went wrong, please try again.');
                // ops, something went wrong. let's back to authorized status,
                context.update({ key: AppContextActionKeyEnum.authStatus, value: AuthStatusEnum.valid });
            });
    }

    // update global state value to open/close sideNav menu in SideNavComp.
    function toggleSideBar() {
        context.update({ key: AppContextActionKeyEnum.showSideBar, value: !context.showSideBar });
    }

    // update tailwind's dark/light mode system
    function toggleMode() {
        const new_mode = !context.darkMode;
        if (new_mode) {
            document.body.classList.add('dark');
        } else {
            document.body.classList.remove('dark');
        }
        // TODO: this state is useless until now. we should remove it at the end of refactor.
        context.update({ key: AppContextActionKeyEnum.darkMode, value: new_mode });
    }

    return (
        <div className="px-3 py-3 lg:px-5 lg:pl-3">
            <div className="flex items-center justify-between">
                <div className="flex items-center justify-start">
                    <button
                        onClick={toggleSideBar}
                        aria-expanded="true"
                        aria-controls="sidebar"
                        className="p-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    >
                        {context.showSideBar ? (
                            <IoClose className="w-6 h-6" />
                        ) : (
                            <GiHamburgerMenu className="w-6 h-6" />
                        )}
                    </button>
                    <a href="#" className="flex ml-2 md:mr-24 max-h-10">
                        <RefineLogo />
                        <span className="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-deep-blue dark:text-gray-200">
                            Base App
                        </span>
                    </a>
                </div>
                <div className="flex items-center">
                    <div className="hidden mr-3 -mb-1 sm:block">
                        <span></span>
                    </div>
                    <button
                        id="theme-toggle"
                        data-tooltip-target="tooltip-toggle"
                        type="button"
                        className="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5"
                        onClick={toggleMode}
                    >
                        {context.darkMode ? <IoSunny className="w-5 h-5" /> : <IoMoon className="w-5 h-5" />}
                    </button>
                    <div
                        id="tooltip-toggle"
                        role="tooltip"
                        className="absolute z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm tooltip opacity-0 invisible"
                        style={{
                            position: 'absolute',
                            inset: '0px auto auto 0px',
                            margin: '0px',
                            transform: 'translate(779px, 60px)',
                        }}
                        data-popper-placement="bottom"
                    >
                        Toggle dark mode
                        <div
                            className="tooltip-arrow"
                            data-popper-arrow=""
                            style={{ position: 'absolute', left: '0px', transform: 'translate(69px, 0px)' }}
                        ></div>
                    </div>

                    <div className="flex items-center ml-3">
                        <Popover>
                            <Popover.Button
                                className="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5"
                                id="user-menu-button-2"
                                aria-expanded="false"
                                data-dropdown-toggle="dropdown-2"
                            >
                                <div>
                                    <span className="sr-only">Open user menu</span>
                                    <BsPersonCircle className="w-5 h-5" />
                                </div>
                            </Popover.Button>
                            <Popover.Panel>
                                <div
                                    className="absolute right-0 z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600 block"
                                    id="dropdown-2"
                                    data-popper-placement="bottom"
                                >
                                    <div className="px-4 py-3" role="none">
                                        <p className="text-sm text-gray-900 dark:text-white" role="none">
                                            {context.user?.full_name}
                                        </p>
                                        <p
                                            className="text-sm font-medium text-gray-900 truncate dark:text-gray-300"
                                            role="none"
                                        >
                                            {context.user?.email}
                                        </p>
                                    </div>
                                    <ul className="py-1" role="none">
                                        <li>
                                            <a
                                                href="#"
                                                onClick={() => {
                                                    if (i18n.language == 'fr') {
                                                        i18n.changeLanguage('en-US');
                                                    } else if (i18n.language == 'en-US') {
                                                        i18n.changeLanguage('fr');
                                                    }
                                                }}
                                                className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                                role="menuitem"
                                            >
                                                {i18n.language == 'en-US' ? 'English' : ''}
                                                {i18n.language == 'fr' ? 'French' : ''}
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="#"
                                                onClick={logout}
                                                className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                                role="menuitem"
                                            >
                                                {t('Sign out')}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </Popover.Panel>
                        </Popover>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default HeaderComp;
