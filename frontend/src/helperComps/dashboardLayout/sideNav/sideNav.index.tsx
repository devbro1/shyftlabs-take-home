import React, { useContext } from 'react';
import { Transition, TransitionClasses } from '@headlessui/react';
import { GlobalContext } from 'context';
import { __SideNavOptions as SideNavData } from './sideNav.data';
import { Link, useLocation, matchPath } from 'react-router-dom';
import { __SideNavStyles as Styles } from './sideNav.styles';
import { useTranslation } from 'react-i18next';

const SideNavComp: React.FC = () => {
    const context = useContext(GlobalContext);
    const { pathname } = useLocation();
    const { t } = useTranslation('leftmenu');

    function can(perms: any) {
        if (typeof perms.functional_permissions === 'function' && context.user != null) {
            if (!perms.functional_permissions(context.user)) {
                return false;
            }
        } else if (
            typeof perms.permissions !== 'undefined' &&
            Array.isArray(perms.permissions) &&
            context.user != null
        ) {
            const intersect = perms.permissions.filter((obj: any) => {
                if (context.user != null) {
                    return context.user.all_permissions.includes(obj);
                }
            });

            if (intersect.length === 0) {
                return false;
            }
        }

        return true;
    }

    // drawer animation for menu toggle action
    const drawerAnimation: TransitionClasses = {
        enter: 'transition ease-in-out duration-300 transform',
        enterFrom: '-translate-x-full',
        enterTo: 'translate-x-0',
        leave: 'transition ease-in-out duration-300 transform',
        leaveFrom: 'translate-x-0',
        leaveTo: '-translate-x-full',
    };

    // expansion animation of menu item click action
    const childrenAnimation: TransitionClasses = {
        enter: 'transition ease-out duration-200',
        enterFrom: 'opacity-0 translate-y-1',
        enterTo: 'opacity-100 translate-y-0',
        leave: 'transition ease-in duration-150',
        leaveFrom: 'opacity-100 translate-y-0',
        leaveTo: 'opacity-0 translate-y-1',
    };

    return (
        <div className={Styles.root} /* break point experience controller element  */>
            <Transition className={Styles.transition} show={context.showSideBar} {...drawerAnimation}>
                {/* devbro logo */}
                <Link className={Styles.logo} to="/">
                    Devbro
                </Link>
                {/* side nav items/links */}
                <ul>
                    {SideNavData.map((mainLink) => {
                        // all matchable links to make this section active
                        const isActive = mainLink.patterns.map((i) => Boolean(matchPath(i, pathname))).includes(true);
                        if (!can(mainLink)) {
                            return '';
                        }
                        return (
                            <li className={Styles.mainItems} key={mainLink.url}>
                                {/* ui thin border on active status */}
                                {isActive ? (
                                    <span className={Styles.mainItemActiveBorder} aria-hidden="true"></span>
                                ) : null}
                                <Link
                                    className={Styles.mainLink(isActive)}
                                    to={mainLink.url}
                                    data-hssm-target="#subMenu1"
                                >
                                    <mainLink.icon />
                                    <span className={Styles.mainLinkTitle}>{mainLink.title}</span>
                                </Link>
                                {/* side nav sub items/links */}
                                {mainLink.children?.length ? (
                                    <Transition show={isActive} {...childrenAnimation}>
                                        <ul className={Styles.childItemContainer} aria-label="submenu">
                                            {mainLink.children.map((childLink) => {
                                                if (!can(childLink)) {
                                                    return '';
                                                }
                                                return (
                                                    <li
                                                        className={Styles.childItems(childLink.url === pathname)}
                                                        key={childLink.url}
                                                    >
                                                        <Link className={Styles.childLink} to={childLink.url}>
                                                            <span className={Styles.childLinkTitle}>
                                                                {t(childLink.title)}
                                                            </span>
                                                        </Link>
                                                    </li>
                                                );
                                            })}
                                        </ul>
                                    </Transition>
                                ) : null}
                            </li>
                        );
                    })}
                </ul>
            </Transition>
        </div>
    );
};

export default SideNavComp;
