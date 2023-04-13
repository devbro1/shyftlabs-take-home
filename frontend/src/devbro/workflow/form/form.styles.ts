export const __styles = {
    root: 'flex flex-col w-full min-h-screen flex p-4',
    form: 'w-full lg:w-4/12 px-6 shadow-lg rounded-lg bg-gray-300 flex flex-col py-10 items-center',
    fields: 'mb-3 w-full',
    loading: 'flex items-center justify-center h-80',
    loadingIcon: 'animate-spin 5-x text-indigo-200',
    menuTransition: {
        enter: 'transition ease-out duration-100',
        enterFrom: 'transform opacity-0 scale-95',
        enterTo: 'transform opacity-100 scale-100',
        leave: 'transition ease-in duration-75',
        leaveFrom: 'transform opacity-100 scale-100',
        leaveTo: 'transform opacity-0 scale-95',
    },
    nodeStyles: {
        root: 'relative inline-block text-left',
        button: (type: string) =>
            `bg-white w-36 h-10 border border-solid rounded text-center align-middle 'p-1'
            h-auto ${borderType(type)}`,
        input: 'border border-solid border-black w-full p-1',
        menu: 'absolute right-0 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none',
        menuItems: 'cursor-pointer px-3 py-1',
    },
};

function borderType(type: string) {
    switch (type) {
        case 'EditableNodeInput':
            return ' border-blue-500';
        case 'EditableNodeDefault':
            return 'border-black';
        case 'EditableNodeOutput':
            return ' border-red-500';
    }
}
