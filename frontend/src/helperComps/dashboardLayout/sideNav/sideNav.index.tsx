import React, { useContext } from 'react';
import { GlobalContext } from 'context';
import { __SideNavOptions as SideNavData } from './sideNav.data';
import SideNavMainEntry from './SideNavMainEntry';
import { canUser } from 'context/actions';

const SideNavComp: React.FC = () => {
    const context = useContext(GlobalContext);

    return (
        <aside
            id="sidebar"
            className={
                'fixed pt-16 lg:pt-0 lg:relative top-0 left-0 z-20 flex flex-col flex-shrink-0 w-64 h-full font-normal duration-75 lg:flex transition-width ' +
                (context.showSideBar ? '' : 'hidden')
            }
            aria-label="Sidebar"
        >
            <div className="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-b rounded-br-lg border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div className="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                    <div className="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <ul className="pb-2 space-y-2">
                            {SideNavData.map((mainLink) => {
                                if (mainLink.permissions && !canUser(mainLink.permissions, context)) {
                                    return '';
                                }
                                return (
                                    <li key={mainLink.url}>
                                        <SideNavMainEntry mainLink={mainLink} />
                                    </li>
                                );
                            })}
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
    );
};

export default SideNavComp;
