import { APIPath } from 'data';
import React, { useEffect, useState } from 'react';
import { RestAPI } from 'scripts';
import { DefaultStyle as Styles } from 'default.styles';
//import { AppointmentType } from 'types';
import { FormComp, SelectComp, DateTimePickerComp, MultiSelect } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useQuery } from '@tanstack/react-query';
import _ from 'lodash';
import { alertService } from 'helperComps/Alert/AlertService';
import moment from 'moment';

const AppointmentFormComp: React.FC = () => {
    const [serviceOptions, setServiceOptions] = useState<any>([]);
    const [storeOptions, setStoreOptions] = useState<any>([]);
    const validationSchema = yup.object().shape({});

    const time_durations = [];
    let m = moment('2000-01-01 00:00');
    for (let $i = 0; $i < 33; $i++) {
        time_durations.push({ title: m.format('HH:mm'), value: m.format('HH:mm') });
        m.add(15, 'minutes');
    }

    const day_time = [];
    m = moment('2000-01-01 00:00');
    for (let $i = 0; $i < 4 * 24; $i++) {
        day_time.push({ title: m.format('HH:mm'), value: m.format('HH:mm') });
        m.add(15, 'minutes');
    }

    const { data: serviceData } = useQuery([APIPath.service.index()], () => {
        return RestAPI.get(APIPath.service.index())
            .then((response: any) => {
                return response.data.data;
            })
            .catch((ex) => {
                throw ex;
            });
    });

    const { data: storeData } = useQuery([APIPath.store.index()], () => {
        return RestAPI.get(APIPath.store.index())
            .then((response: any) => {
                return response.data.data;
            })
            .catch((ex) => {
                throw ex;
            });
    });

    useEffect(() => {
        if (!serviceData) {
            return;
        }
        const val: any = [];
        _.forEach(serviceData, (value) => {
            val.push({ title: value.name, value: value.id });
        });

        setServiceOptions(val);
    }, [serviceData]);

    useEffect(() => {
        if (!storeData) {
            return;
        }
        const val: any = [];
        _.forEach(storeData, (value) => {
            val.push({ title: value.store_no + ' - ' + value.name, value: value.id });
        });

        setStoreOptions(val);
    }, [storeData]);

    const { reset, handleSubmit, control, getValues, setError } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: {},
    });

    function createNewAppointments() {
        getValues();
        RestAPI.post(APIPath.appointment.index(), getValues())
            .then((response: any) => {
                alertService.info(response.data.message);
                reset();
            })
            .catch((error) => {
                _.forEach(error.response.data.errors, (value, key) => {
                    setError(key, {
                        message: RestAPI.getErrorMessage('', Object.keys(value)[0], Object.values(value)[0]),
                    });
                });
                alertService.error('Something went wrong, please try again');
            });
    }

    return (
        <FormComp
            onSubmit={handleSubmit(() => {
                createNewAppointments();
            })}
            title="Create New Appointments"
            className={Styles.form.root}
            buttonTitle="Create"
        >
            <div className={Styles.form.row}>
                <DateTimePickerComp
                    className={Styles.form.fields}
                    name="date_start"
                    control={control}
                    showTime={false}
                    title="Start Date"
                    outputFormat="YYYY-MM-DD"
                />
                <DateTimePickerComp
                    className={Styles.form.fields}
                    name="date_end"
                    control={control}
                    title="End Date"
                    showTime={false}
                    outputFormat="YYYY-MM-DD"
                />
            </div>
            <div className={Styles.form.row}>
                <SelectComp
                    className={Styles.form.fields}
                    name="time_start"
                    control={control}
                    title="Start Time"
                    options={day_time}
                />
                <SelectComp
                    className={Styles.form.fields}
                    name="time_end"
                    control={control}
                    title="End Time"
                    options={day_time}
                />
            </div>
            <div className={Styles.form.row}>
                <SelectComp
                    className={Styles.form.fields}
                    name="appointment_duration"
                    control={control}
                    type="text"
                    title="Appointment Duration"
                    options={time_durations}
                />
                <SelectComp
                    className={Styles.form.fields}
                    name="appointment_padding"
                    control={control}
                    title="Appointment Padding"
                    options={time_durations}
                />
            </div>
            <div className={Styles.form.row}>
                <MultiSelect
                    className={Styles.form.fields}
                    name="services"
                    control={control}
                    type="text"
                    title="Services"
                    options={serviceOptions}
                />
            </div>
            <div className={Styles.form.row}>
                <MultiSelect
                    className={Styles.form.fields}
                    name="stores"
                    control={control}
                    type="text"
                    title="Stores"
                    options={storeOptions}
                />
            </div>
        </FormComp>
    );
};

export default AppointmentFormComp;
