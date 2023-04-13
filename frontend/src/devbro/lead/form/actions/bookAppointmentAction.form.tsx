import { APIPath, RoutePath } from 'data';
import React, { Suspense, useEffect, useState } from 'react';
import { RestAPI } from 'scripts';
import { __FormStyle as Styles } from '../form.styles';
//import { LeadType } from 'types';
import { FormComp, ButtonComp, SelectComp } from 'utils';
import { DateTimePickerComp } from 'utils';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { FieldValues, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import { FaSpinner } from 'react-icons/fa';
//import { useQueryClient } from '@tanstack/react-query';
import { alertService } from 'helperComps/Alert/AlertService';
import { LeadsApi } from 'api/LeadsApi';
import { UsersApi } from 'api/UsersApi';
import { __SelectOptionType } from 'utils/select/select.types';

type PageParams = {
    id: string;
    action_id: string;
};

// create/edit service page
const BookAppointmentFormComp: React.FC = () => {
    // id determine whether this component render for edit page or not
    const { id, action_id } = useParams<PageParams>();
    const navigate = useNavigate();
    const validationSchema = yup.object().shape({});
    const [appointment_owner_options, setAppointmentOwnerOptions] = useState<__SelectOptionType[]>([]);
    const [appointments_options, setAppointmentOptions] = useState<__SelectOptionType[]>([]);

    if (!id || !action_id) {
        return <></>;
    }
    const { control, getValues, watch } = useForm<FieldValues>({
        resolver: yupResolver(validationSchema),
        defaultValues: {},
    });
    const formData = watch();

    const { data: lead } = LeadsApi.get(parseInt(id));
    const { data: leadAction } = LeadsApi.getAction(parseInt(id), parseInt(action_id));
    const { data: appointments } = UsersApi.getAppointments(
        formData.appointment_owner,
        {
            enabled: typeof formData.appointment_owner !== 'undefined' && typeof formData.date !== 'undefined',
        },
        { 'filter[on]': formData.date },
    );

    function submit() {
        RestAPI.put(APIPath.lead.actions(id, action_id), {
            appointment_id: getValues()['appointment_id'],
        })
            .then((response: any) => {
                alertService.success(response.data.message || 'Lead was updated Successfully');
                navigate(RoutePath.lead.__index + '/' + id);
            })
            .catch((error) => {
                alertService.error(error.response.data.message || 'Something went wrong, please try again');
                return;
            });
        return;
    }

    function cancel() {
        navigate(RoutePath.lead.__index + '/' + id);
        return;
    }

    useEffect(() => {
        const options: __SelectOptionType[] = [];

        if (lead?.owners) {
            lead?.owners.forEach((owner: any) => {
                options.push({ value: owner.provider_id, title: owner.provider.full_name } as __SelectOptionType);
            });

            setAppointmentOwnerOptions(options);
        }
    }, [lead]);

    useEffect(() => {
        const options: __SelectOptionType[] = [];

        if (appointments) {
            appointments.forEach((appointment: any) => {
                options.push({ value: appointment.id, title: appointment.dt_start } as __SelectOptionType);
            });

            setAppointmentOptions(options);
        }
    }, [appointments]);

    return (
        <Suspense
            fallback={
                <div className={Styles.loading}>
                    <FaSpinner size={48} className={Styles.loadingIcon} />
                </div>
            }
        >
            <FormComp title={leadAction?.alternative_name} className={Styles.root}>
                <SelectComp
                    className={Styles.fields}
                    name="appointment_owner"
                    control={control}
                    options={appointment_owner_options}
                    title="Appointment Owner"
                    placeholder="- please select one -"
                />
                <DateTimePickerComp control={control} name="date" showTime={false} outputFormat={'YYYY-MM-DD'} />

                <SelectComp
                    className={Styles.fields}
                    name="appointment_id"
                    control={control}
                    options={appointments_options}
                    title="Appointment"
                    placeholder="- please select one -"
                />

                <div className={Styles.row}>
                    <ButtonComp onClick={cancel} className={Styles.column}>
                        Cancel
                    </ButtonComp>
                    <ButtonComp onClick={submit} className={Styles.column}>
                        Set appointment
                    </ButtonComp>
                </div>
            </FormComp>
        </Suspense>
    );
};

export default BookAppointmentFormComp;
