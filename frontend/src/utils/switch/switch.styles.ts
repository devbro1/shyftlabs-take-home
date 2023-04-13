export const __SwitchStyles = {
    root: `flex items-center`,
    title: (error?: boolean) =>
        `mr-4 block text-sm font-medium ${
            error ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-100'
        }`,
    body: (hasValue?: boolean) =>
        `${
            hasValue ? 'bg-purple-200' : 'bg-gray-200'
        } relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500`,
    toggle: (hasValue?: boolean) =>
        `${
            hasValue ? 'bg-purple-600 translate-x-6' : 'translate-x-1 bg-white'
        } inline-block w-4 h-4 transform bg-white rounded-full transition-transform`,
    error: 'text-xs text-red-600 dark:text-red-400',
    description: 'block text-xs dark:text-gray-100',
};
