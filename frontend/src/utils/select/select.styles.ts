export const __SelectStyles = {
    input: (error: boolean, hasValue: boolean) => {
        //return 'bg-gray-50 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500';
        return `shadow-sm bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white ${
            error
                ? 'border-red-400 dark:border-red-400 placeholder-red-500 text-red-900 '
                : `border-gray-300 placeholder-gray-500 text-gray-900 dark:focus:ring-primary-500 dark:focus:border-primary-500 ${
                      hasValue ? 'text-gray-900' : 'text-gray-500'
                  }`
        } focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm`;
    },

    title: (error?: boolean) =>
        `block text-sm font-medium ${error ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-100'}`,
    error: 'text-xs text-red-600 dark:text-red-400',
    description: 'block text-xs dark:text-gray-100',
};
