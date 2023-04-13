import { RoutePath } from 'data';
import { FaBeer } from 'react-icons/fa';
//import { PermissionType, UserType } from 'types';

// side nave content data (dummy data yet)
export const __SideNavOptions = [
    {
        url: RoutePath.announcement.__index,
        title: 'Announcements',
        icon: FaBeer,
        patterns: ['/announcements/*'],
        children: [{ url: RoutePath.announcement.new(), title: 'Add Announcement', icon: FaBeer }],
        // functional_permissions: (user: UserType) => {
        //     return true;
        // },
    },
    {
        url: RoutePath.user.__index,
        title: 'User Management',
        icon: FaBeer,
        patterns: ['/users/*', '/permissions/*', '/roles/*'],
        children: [
            { url: RoutePath.user.__index, title: 'User List' },
            { url: RoutePath.user.new(), title: 'Add User' },
            { url: RoutePath.role.__index, title: 'Roles' },
            { url: RoutePath.role.new(), title: 'Add Role' },
            { url: RoutePath.permission.__index, title: 'Permissions' },
            { url: RoutePath.permission.new(), title: 'Add Permission' },
        ],
    },
    {
        url: RoutePath.lead.__index,
        title: 'Leads',
        icon: FaBeer,
        patterns: ['/leads/*'],
        children: [{ url: RoutePath.lead.__index, title: 'Leads List' }],
    },
    {
        url: RoutePath.store.__index,
        title: 'Stores',
        icon: FaBeer,
        patterns: ['/stores/*', '/companies/*'],
        children: [
            { url: RoutePath.store.__index, title: 'Stores List' },
            { url: RoutePath.store.new(), title: 'Add Store' },
            { url: RoutePath.company.__index, title: 'Companies List' },
            { url: RoutePath.company.new(), title: 'Add Company' },
        ],
    },
    {
        url: RoutePath.appointment.new(),
        title: 'Appointments',
        icon: FaBeer,
        patterns: ['/appointments/*'],
        children: [
            { url: RoutePath.appointment.weeks(''), title: 'Calendar' },
            { url: RoutePath.appointment.new(), title: 'New Appointments' },
        ],
        // functional_permissions: (user: UserType) => {
        //     return (
        //         user.all_permissions.includes('manage self appointments') ||
        //         user.all_permissions.includes('manage all appointments') ||
        //         user.all_permissions.includes('have appointments') ||
        //         user.all_permissions.includes('manage company appointments')
        //     );
        // },
        permissions: [
            'manage self appointments',
            'manage all appointments',
            'have appointments',
            'manage company appointments',
        ],
    },
    {
        url: RoutePath.service.__index,
        title: 'Services',
        icon: FaBeer,
        patterns: ['/services/*'],
        children: [
            { url: RoutePath.service.__index, title: 'Services List' },
            { url: RoutePath.service.new(), title: 'Add Service' },
        ],
    },
    {
        url: RoutePath.workflow.__index,
        title: 'Workflows',
        icon: FaBeer,
        patterns: ['/workflows/*', '/actions/*'],
        children: [
            { url: RoutePath.workflow.__index, title: 'Workflows' },
            { url: RoutePath.workflow.new(), title: 'Add Workflow' },
            { url: RoutePath.action.__index, title: 'Lead Actions' },
        ],
    },
    {
        url: RoutePath.translation.__index,
        title: 'Settings',
        icon: FaBeer,
        patterns: ['/translations/*'],
        children: [
            { url: RoutePath.translation.__index, title: 'Translation List' },
            { url: RoutePath.translation.new(), title: 'Add Translation' },
        ],
    },
];
