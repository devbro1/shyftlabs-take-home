import React from 'react';
import { Menu, Transition } from '@headlessui/react';
import { __styles } from './form.styles';
import { __ChartNodeComp } from 'utils';

const EditableNode: React.FC = (props: any) => {
    function editActions() {
        props.data.editAction();
    }

    function editName() {
        const new_label = window.prompt('Action Label', props.data.label);
        if (new_label) {
            props.data.onNameChanged({ id: props.id, label: new_label });
        }
    }

    return (
        <__ChartNodeComp {...props}>
            <Menu as="div">
                <Menu.Button>{props.data.label}</Menu.Button>
                <Transition {...__styles.menuTransition}>
                    <Menu.Items className={__styles.nodeStyles.menu}>
                        <Menu.Item as="div" onClick={editName} className={__styles.nodeStyles.menuItems}>
                            Edit Name
                        </Menu.Item>
                        {props.data.serverSideSaved && (
                            <Menu.Item as="div" onClick={editActions} className={__styles.nodeStyles.menuItems}>
                                Edit Actions
                            </Menu.Item>
                        )}
                    </Menu.Items>
                </Transition>
            </Menu>
        </__ChartNodeComp>
    );
};

export { EditableNode as EditableNode };
