import React, { useState } from 'react';
import { Controller } from 'react-hook-form';
import { FileInputStyles as Styles } from './fileInput.styles';
import { __FileInputProps } from './fileInput.types';
import { __RestAPI as RestAPI } from 'scripts/api';
import { APIPath } from 'data';
import { ButtonComp } from 'utils';

// text input component compatible with controller logic
const __TextInputComp: React.FC<__FileInputProps> = (props: __FileInputProps) => {
    const [fieldValue, setFieldValue] = useState<any | false>(false);
    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => {
                if (field.value && !fieldValue && isFinite(field.value)) {
                    RestAPI.get(APIPath.file.index(field.value) + '?info_only=true').then((resp: any) => {
                        setFieldValue(resp.data);
                        field.onChange(resp.data.id);
                    });
                }
                function fileAttached(event: any) {
                    field.onChange('Uploading...');
                    props.clearErrors(props.name);
                    const formData = new FormData();
                    formData.append('file', event.target.files[0]);
                    RestAPI.post(APIPath.file.post(), formData, {
                        'Content-Type': 'multipart/form-data',
                    })
                        .then((resp: any) => {
                            //{"filename":"image002.png","mimetype":"image\/png","extension":"png","size":169006,"path":"public\/iG290smx045n9Hy1h0qfJdf10X8fxKbytzfU8vpu.png","updated_at":"2022-09-03T01:32:36.000000Z","created_at":"2022-09-03T01:32:36.000000Z","id":2}
                            setFieldValue(resp.data.file);
                            field.onChange(resp.data.file.id);
                        })
                        .catch((error) => {
                            field.onChange('');
                            if ([null, 0].includes(error.response.status)) {
                                props.setError(props.name, {
                                    type: 'custom',
                                    message: 'Unknown error during upload, is the file too large?',
                                });
                            } else if ([413].includes(error.response.status)) {
                                props.setError(props.name, { type: 'custom', message: 'File is too large' });
                            } else {
                                props.setError(props.name, {
                                    type: 'custom',
                                    message: error.response.data.error.file[0],
                                });
                            }
                        });
                }

                function deleteFile() {
                    setFieldValue(false);
                    field.onChange('');
                }

                let actualField: any = '';

                if (fieldValue === false) {
                    actualField = (
                        <input
                            placeholder={props.placeholder}
                            type="file"
                            onChange={fileAttached}
                            className={Styles.input(fieldState.invalid)}
                            value=""
                        />
                    );
                } else {
                    actualField = (
                        <div>
                            Uploaded file: {fieldValue.filename} <ButtonComp onClick={deleteFile}>DELETE</ButtonComp>
                        </div>
                    );
                }

                return (
                    <div className={props.className}>
                        {/* input optional title */}
                        {props.title ? <span className={Styles.title(fieldState.invalid)}>{props.title}</span> : null}
                        {actualField}
                        {/* input error message */}
                        {fieldState.invalid && fieldState.error?.message ? (
                            <span className={Styles.error}>{fieldState.error?.message}</span>
                        ) : null}
                        {/* input optional description */}
                        {props.description ? <span className={Styles.description}>{props.description}</span> : null}
                    </div>
                );
            }}
        />
    );
};

export default __TextInputComp;
