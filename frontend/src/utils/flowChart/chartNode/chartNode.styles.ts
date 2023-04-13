export const __ChartNodeStyles = {
    root: 'relative inline-block text-left',
    button: (type: string, editable?: boolean) =>
        `bg-white w-36 h-10 border border-solid rounded text-center align-middle ${
            editable ? '' : 'p-1'
        } h-auto ${borderType(type)}`,
    input: 'border border-solid border-black w-full p-1',
    menu: 'absolute right-0 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none',
    menuItems: 'cursor-pointer px-3 py-1',
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
