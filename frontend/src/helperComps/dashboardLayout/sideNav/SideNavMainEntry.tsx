import { useState, useContext } from 'react';
import { Link, useLocation, matchPath } from 'react-router-dom';
import { Transition, TransitionClasses } from '@headlessui/react';
import { useTranslation } from 'react-i18next';
import { BiChevronDown, BiChevronUp } from 'react-icons/bi';
import { canUser } from 'context/actions';
import { GlobalContext } from 'context';

function SideNavMainEntry(props: any) {
    const context = useContext(GlobalContext);
    const mainLink = props.mainLink;
    const { pathname } = useLocation();
    const [isActive, setIsActive] = useState<boolean>(
        mainLink.patterns.map((i: string) => Boolean(matchPath(i, pathname))).includes(true),
    );
    const { t } = useTranslation('leftmenu');

    const childrenAnimation: TransitionClasses = {
        enter: 'transition ease-out duration-200',
        enterFrom: 'opacity-0 translate-y-1',
        enterTo: 'opacity-100 translate-y-0',
        leave: 'transition ease-in duration-150',
        leaveFrom: 'opacity-100 translate-y-0',
        leaveTo: 'opacity-0 translate-y-1',
    };

    return (
        <>
            {/* <Link className={Styles.mainLink(isActive)} to={mainLink.url} data-hssm-target="#subMenu1"> */}
            <button
                type="button"
                className="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                aria-controls="dropdown-layouts"
                data-collapse-toggle="dropdown-layouts"
                onClick={() => {
                    setIsActive(!isActive);
                }}
            >
                <mainLink.icon />
                <span className="flex-1 ml-3 text-left whitespace-nowrap">{t(mainLink.title)}</span>
                {isActive ? <BiChevronDown className="w-6 h-6" /> : <BiChevronUp className="w-6 h-6" />}
            </button>
            {/* </Link> */}
            {mainLink.children?.length ? (
                <Transition show={isActive} {...childrenAnimation}>
                    <ul id="dropdown-layouts" className="py-2 space-y-2" /* hidden */>
                        {mainLink.children.map((childLink: any) => {
                            if (childLink.permissions && !canUser(childLink.permissions, context)) {
                                return '';
                            }
                            return (
                                <li key={childLink.url}>
                                    <Link
                                        to={childLink.url}
                                        className="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                    >
                                        {t(childLink.title)}
                                    </Link>
                                </li>
                            );
                        })}
                    </ul>
                </Transition>
            ) : null}
        </>
    );
}

export default SideNavMainEntry;
