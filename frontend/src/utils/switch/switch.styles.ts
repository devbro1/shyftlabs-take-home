export const __SwitchStyles = {
    root: `flex items-center`,
    title: (error?: boolean) =>
        `mr-4 block text-sm font-medium ${
            error ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-100'
        }`,
    body: (hasValue?: boolean) =>
        `${
            hasValue ? 'bg-blue-200' : 'bg-gray-200'
        } relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500`,
    toggle: (hasValue?: boolean) =>
        `${
            hasValue ? 'bg-blue-600 translate-x-6' : 'bg-white translate-x-1'
        } inline-block w-4 h-4 transform rounded-full transition-transform`,
    error: 'text-xs text-red-600 dark:text-red-400',
    description: 'block text-xs dark:text-gray-100',
};
