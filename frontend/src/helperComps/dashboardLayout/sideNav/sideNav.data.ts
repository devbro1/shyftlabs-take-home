import { RoutePath } from 'data';
import {
    FaBeer,
    FaDatabase,
    FaDisease,
    FaExchangeAlt,
    FaFileExport,
    FaHospitalSymbol,
    FaNewspaper,
    FaUser,
} from 'react-icons/fa';
import { RiSettings3Fill } from 'react-icons/ri';
//import { PermissionType, UserType } from 'types';

// side nave content data (dummy data yet)
export const __SideNavOptions = [
    {
        url: RoutePath.announcement.__index,
        title: 'Announcements',
        icon: FaNewspaper,
        patterns: ['/announcements/*'],
        children: [
            { url: RoutePath.announcement.__index, title: 'Announcements', icon: FaBeer },
            {
                url: RoutePath.announcement.new(),
                title: 'Add Announcement',
                permissions: ['create announcement'],
            },
        ],
        // functional_permissions: (user: UserType) => {
        //     return true;
        // },
    },
    {
        url: RoutePath.user.__index,
        title: 'User Management',
        icon: FaUser,
        patterns: ['/users/*', '/permissions/*', '/roles/*'],
        children: [
            { url: RoutePath.user.__index, title: 'User List' },
            { url: RoutePath.user.new(), title: 'Add User', permissions: ['add user'] },
            { url: RoutePath.role.__index, title: 'Roles', permissions: ['manage roles'] },
            { url: RoutePath.role.new(), title: 'Add Role', permissions: ['manage roles'] },
            { url: RoutePath.permission.__index, title: 'Permissions', permissions: ['manage permissions'] },
            { url: RoutePath.permission.new(), title: 'Add Permission', permissions: ['add permission'] },
        ],
        permissions: ['view users', 'add user', 'update user'],
    },
    {
        url: RoutePath.translation.__index,
        title: 'Settings',
        icon: RiSettings3Fill,
        patterns: ['/translations/*'],
        children: [
            { url: RoutePath.translation.__index, title: 'Translation List' },
            { url: RoutePath.translation.new(), title: 'Add Translation' },
        ],
        permissions: ['configuration'],
    },
];
