export const __SelectStyles = {
    input: (error: boolean, hasValue: boolean) =>
        `block w-full py-2 px-3 border bg-white rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ${
            error ? 'border-red-400 text-red-900' : `border-gray-300 ${hasValue ? 'text-gray-900' : 'text-gray-500'}`
        }`,

    title: (error?: boolean) =>
        `block text-sm font-medium ${error ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-100'}`,
    error: 'text-xs text-red-600 dark:text-red-400',
    description: 'block text-xs dark:text-gray-100',
};
