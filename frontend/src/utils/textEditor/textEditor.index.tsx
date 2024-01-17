import React, { useState } from 'react';
import { Controller } from 'react-hook-form';
import { __TextEditorStyles as Styles } from './textEditor.styles';
import { __TextEditorProps } from './textEditor.types';
import { Editor } from 'react-draft-wysiwyg';
import 'react-draft-wysiwyg/dist/react-draft-wysiwyg.css';
import draftToHtml from 'draftjs-to-html';
import { convertToRaw, ContentState, RawDraftContentState } from 'draft-js';
import htmlToDraft from 'html-to-draftjs';

// text editor component compatible with controller logic
const __TextEditorComp: React.FC<__TextEditorProps> = (props: __TextEditorProps) => {
    // manually focus handling because editor is not input tag.
    const [focus, setFocus] = useState<boolean>(false);
    function onChangeHandler(e: RawDraftContentState, controllerChange: (...event: any[]) => void) {
        const value = draftToHtml(e);
        // check if content is empty or not (for example empty <p></p>)
        const isFull = /(<.+>).+(<\/.*>)/.test(value);
        controllerChange(isFull ? value : '');
    }

    // parse html string to default value
    function parseHtmlToContent(html: string) {
        return convertToRaw(ContentState.createFromBlockArray(htmlToDraft(html).contentBlocks));
    }
    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => (
                <div className={props.className}>
                    {/* input optional title */}
                    {props.title ? <span className={Styles.title(fieldState.invalid)}>{props.title}</span> : null}
                    {/* main editor */}
                    <Editor
                        defaultContentState={parseHtmlToContent(field.value)}
                        toolbarClassName={Styles.toolbar}
                        wrapperClassName={Styles.input(fieldState.invalid, focus)}
                        onContentStateChange={(e: any) => onChangeHandler(e, field.onChange)}
                        onBlur={() => {
                            setFocus(false);
                            field.onBlur();
                        }}
                        onFocus={() => {
                            setFocus(true);
                        }}
                        ref={field.ref}
                    />
                    {/* input error message */}
                    {fieldState.invalid && fieldState.error?.message ? (
                        <span className={Styles.error}>{fieldState.error?.message}</span>
                    ) : null}
                    {/* input optional description */}
                    {props.description ? <span className={Styles.description}>{props.description}</span> : null}
                </div>
            )}
        />
    );
};

export default __TextEditorComp;
