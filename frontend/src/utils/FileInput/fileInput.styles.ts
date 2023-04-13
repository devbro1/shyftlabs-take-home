export const FileInputStyles = {
    input: (error?: boolean) =>
        `appearance-none rounded relative block w-full px-3 py-2 border ${
            error
                ? 'border-red-400 placeholder-red-500 text-red-900'
                : 'border-gray-300 placeholder-gray-500 text-gray-900'
        } focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm`,

    title: (error?: boolean) =>
        `block text-sm font-medium ${error ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-100'}`,
    error: 'text-xs text-red-600 dark:text-red-400',
    description: 'block text-xs dark:text-gray-100',
};
