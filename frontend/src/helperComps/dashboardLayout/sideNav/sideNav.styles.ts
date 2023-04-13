export const __SideNavStyles = {
    root: 'h-screen lg:h-auto lg:relative fixed top-0 left-0 z-30',
    transition:
        'h-full z-30 flex-shrink-0 w-64 bg-white dark:bg-gray-800 pt-16 lg:pt-4 pb-4 text-gray-500 dark:text-gray-400',
    logo: 'ml-6 mb-6 text-lg font-bold text-gray-800 dark:text-gray-200',
    mainItems: 'relative px-6 py-3',
    mainItemActiveBorder: 'absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg',
    mainLink: (isActive: boolean) =>
        `inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 ${
            isActive ? 'text-gray-800 dark:text-gray-100' : ''
        }`,
    mainLinkTitle: 'ml-4',
    childItemContainer:
        'p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900',
    childItems: (isActive: boolean) =>
        `px-2 py-1 transition-colors duration-150 ${
            isActive ? 'text-gray-800 dark:text-gray-200 underline' : 'hover:text-gray-800 dark:hover:text-gray-200'
        }`,
    childLink: 'w-full',
    childLinkTitle: 'media-body align-self-center',
};
