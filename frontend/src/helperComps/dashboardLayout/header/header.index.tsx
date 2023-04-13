import React, { useContext } from 'react';
import { MdMenu } from 'react-icons/md';
import { FaMoon, FaSun, FaBell, FaUser } from 'react-icons/fa';
import { IoSettingsOutline as OutlineLogoutIcon } from 'react-icons/io5';
import { Popover, Transition, TransitionClasses } from '@headlessui/react';
import { GlobalContext } from 'context';
import { AppContextActionKeyEnum, AuthStatusEnum } from 'types';
import { __HeaderStyles as Styles } from './header.styles';
import { CookiesInterface, RestAPI } from 'scripts';
import { APIPath } from 'data';
import { toast } from 'react-toastify';

// header component of dashboard base layout
const HeaderComp: React.FC = () => {
    const context = useContext(GlobalContext);

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

    // profile menu animation
    const animationProps: TransitionClasses = {
        enter: 'transition ease-out duration-200',
        enterFrom: 'opacity-0 translate-y-1',
        enterTo: 'opacity-100 translate-y-0',
        leave: 'transition ease-in duration-150',
        leaveFrom: 'opacity-100 translate-y-0',
        leaveTo: 'opacity-0 translate-y-1',
    };

    return (
        <header className={Styles.root}>
            {/* side nav toggle button */}
            <button className={Styles.menuButton} onClick={toggleSideBar} aria-label="Menu">
                <MdMenu className={Styles.menuButtonIcon} aria-hidden="true" />
            </button>
            <ul className={Styles.actionsList}>
                {/* <!-- light/dark mode toggler --> */}
                <button className={Styles.modeButton} onClick={toggleMode} aria-label="Toggle color mode">
                    {context.darkMode ? (
                        <FaSun className={Styles.icon} aria-hidden="true" />
                    ) : (
                        <FaMoon className={Styles.icon} aria-hidden="true" />
                    )}
                </button>
                {/* <!-- Notifications menu --> */}
                <li className={Styles.notification}>
                    <Popover>
                        <Popover.Button className="align-middle">
                            <FaBell className={Styles.icon} aria-hidden="true" />
                            {/* <!-- Notification badge --> */}
                            <span aria-hidden="true" className={Styles.notificationBadge}></span>
                        </Popover.Button>
                        <Transition {...animationProps}>
                            <Popover.Panel className={Styles.notificationContent}>
                                {/* TODO: static content. should be update */}
                                <div>Messages</div>
                            </Popover.Panel>
                        </Transition>
                    </Popover>
                </li>
                {/* <!-- Profile menu --> */}
                <li className="relative">
                    <Popover>
                        <Popover.Button className={Styles.profileButton} aria-label="Account" aria-haspopup="true">
                            <FaUser className={Styles.icon} aria-hidden="true" />
                        </Popover.Button>
                        <Transition {...animationProps}>
                            <Popover.Panel className={Styles.profileContent}>
                                {/* <a href="#" className="content-center">
                                    <OutlinePersonIcon
                                        className="w-4 h-4 mr-3 inline-block align-middle"
                                        aria-hidden="true"
                                    />
                                    <span>Profile</span>
                                </a> */}
                                <button onClick={logout}>
                                    <OutlineLogoutIcon className={Styles.profileIcon} aria-hidden="true" />
                                    <span>Log out</span>
                                </button>
                            </Popover.Panel>
                        </Transition>
                    </Popover>
                </li>
            </ul>
        </header>
    );
};

export default HeaderComp;
