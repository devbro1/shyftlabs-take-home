import React, { useEffect, useState } from 'react';
import { Controller } from 'react-hook-form';
import { __Styles as Styles } from './DateTimePicker.styles';
import { __DateTimePickerProps } from './DateTimePicker.types';
import moment from 'moment';
import { GrCalendar } from 'react-icons/gr';
import {
    HiOutlineChevronLeft,
    HiOutlineChevronRight,
    HiOutlineChevronDoubleLeft,
    HiOutlineChevronDoubleRight,
} from 'react-icons/hi';
import { Popover, Transition, TransitionClasses } from '@headlessui/react';

// text input component compatible with controller logic
const DateTimePicker: React.FC<__DateTimePickerProps> = (props: __DateTimePickerProps) => {
    return (
        <Controller
            control={props.control}
            name={props.name}
            render={({ field, fieldState }) => {
                const [is_visible, setVisible] = useState(false);
                const calendar_rows = [];
                let calendar_headers = [];
                let row = [];
                let hover_class = Styles.calendar;
                let value_moment = moment();
                if (Date.parse(field.value)) {
                    value_moment = moment(field.value);
                }

                useEffect(() => {
                    document.addEventListener(
                        'keydown',
                        (e) => {
                            if (['Escape', 'Tab'].includes(e.code)) setVisible(false);
                        },
                        false,
                    );
                }, []);

                if (is_visible) {
                    hover_class = 'visible ' + hover_class;
                } else {
                    hover_class = 'invisible ' + hover_class;
                }

                const d = value_moment.clone().startOf('month');
                while (d.isoWeekday() !== props.beginningOfWeek) {
                    d.add(-1, 'day');
                }
                d.add(-1, 'day');

                for (let i = 0; i < 7 * 6; i++) {
                    d.add(1, 'day');

                    //render last month
                    const day_of_month = d.date();
                    const callback_value = d.format('YYYY-MM-DD');
                    let cell_class = '';

                    if (d.format('YYYYMM') === value_moment.format('YYYYMM')) {
                        if (d.format('YYYYMMDD') === moment().format('YYYYMMDD')) {
                            cell_class = Styles.date_td_today;
                        } else {
                            cell_class = Styles.date_td;
                        }
                    } else {
                        cell_class = Styles.date_td_not_this_month;
                    }

                    if (d.format('YYYY-MM-DD') === value_moment.format('YYYY-MM-DD')) {
                        cell_class = Styles.date_td_selected;
                    }
                    row.push(
                        <td
                            className={cell_class}
                            onClick={() => {
                                onItemClick('date', callback_value);
                                if (!props.showTime) setVisible(false);
                            }}
                        >
                            {day_of_month}
                        </td>,
                    );

                    //render this month
                    if (row.length === 7) {
                        calendar_rows.push(<tr>{row}</tr>);
                        row = [];
                    }
                }

                calendar_headers = ['']
                    .concat(props.week_titles || '')
                    .concat(props.week_titles || '')
                    .slice(props.beginningOfWeek, props.beginningOfWeek + 7)
                    .map((val, index) => {
                        return (
                            <th className="w-8 h-8" key={'date-picker-key-' + index}>
                                {val}
                            </th>
                        );
                    });

                function onItemClick(item: any, val: any) {
                    if (['second', 'minute', 'hour', 'month', 'year'].includes(item)) {
                        value_moment.set(item, val);
                    } else if (['date'].includes(item)) {
                        value_moment.set('year', val.split('-')[0]);
                        value_moment.set('month', val.split('-')[1] - 1);
                        value_moment.set('date', val.split('-')[2]);
                    }

                    if (!props.showTime) {
                        value_moment.set('second', 0);
                        value_moment.set('minute', 0);
                        value_moment.set('hour', 0);
                    }

                    field.onChange(value_moment.format(props.outputFormat));
                }

                function generateList(sc: string) {
                    if (props.generateList) {
                        return props.generateList(sc);
                    }

                    if (sc === 'meridiem') {
                        return ['AM', 'PM'];
                    }
                    const sections: any = {
                        hour: { min: 0, max: 24, step: 1 },
                        minute: { min: 0, max: 60, step: 5 },
                        second: { min: 0, max: 60, step: 15 },
                    };

                    const q = sections[sc];
                    const rc = [];
                    for (let i = q.min; i < q.max; i += q.step) {
                        let text = ('' + i).padStart(2, '0');
                        if (sc === 'hour') {
                            text = ('' + i).padStart(2, '0');
                            if (i > 12) {
                                text = ('' + (i - 12)).padStart(2, '0');
                            }

                            if (i >= 12) {
                                text += ' PM';
                            } else {
                                text += ' AM';
                            }
                        }
                        rc.push({ value: i, text: text });
                    }
                    return rc;
                }

                let time_drop_list: any = null;

                if (props.showTime) {
                    const hour_drop_list = (props.timePortions || []).map((sc: any) => {
                        const list = generateList(sc).map((val: any, index: number) => {
                            let hclass = Styles.hour_il;
                            let v = value_moment.get(sc).toString();
                            if (sc === 'meridiem') {
                                v = value_moment.format('A');
                            }

                            if (v == val.value) {
                                hclass = Styles.hour_il_selected;
                            }

                            return (
                                <li
                                    key={'date-picker-li-key' + index}
                                    className={hclass}
                                    onClick={() => {
                                        onItemClick(sc, val.value);
                                    }}
                                >
                                    {val.text}
                                </li>
                            );
                        });

                        return (
                            <div key={'date-picker-key-' + sc}>
                                <ul className={Styles.hour_ul}>{list}</ul>
                            </div>
                        );
                    });

                    time_drop_list = (
                        <div className={Styles.time_div}>
                            <div className={Styles.header_time}>&nbsp;</div>
                            <div className={Styles.hour_div}>{hour_drop_list}</div>
                        </div>
                    );
                }

                function onFocus() {
                    setVisible(true);
                }

                const input_props = {};
                //delete input_props.type;

                const animationProps: TransitionClasses = {
                    enter: 'transition ease-out duration-200',
                    enterFrom: 'opacity-0 translate-y-1',
                    enterTo: 'opacity-100 translate-y-0',
                    leave: 'transition ease-in duration-150',
                    leaveFrom: 'opacity-100 translate-y-0',
                    leaveTo: 'opacity-0 translate-y-1',
                };

                return (
                    <Popover>
                        <Popover.Button className="align-middle">
                            <div className="relative">
                                <input
                                    type="text"
                                    {...input_props}
                                    onFocus={onFocus}
                                    className={Styles.input(fieldState.invalid)}
                                    value={field.value}
                                />
                                {props.fieldIcon}
                            </div>
                        </Popover.Button>
                        <Transition {...animationProps}>
                            <Popover.Panel className={Styles.notificationContent}>
                                <div className={hover_class}>
                                    <div className="flex flex-row">
                                        <div className="">
                                            <div className={Styles.header_calendar}>
                                                <div
                                                    className="flex-none py-2"
                                                    onClick={() => {
                                                        onItemClick('year', value_moment.year() - 1);
                                                    }}
                                                >
                                                    {props.icon_last_year}
                                                </div>
                                                <div
                                                    className="flex-none py-2"
                                                    onClick={() => {
                                                        onItemClick('month', value_moment.month() - 1);
                                                    }}
                                                >
                                                    {props.icon_last_month}
                                                </div>
                                                <div className="flex-grow py-2 text-center">
                                                    {value_moment.format(props.topFormat)}
                                                </div>
                                                <div
                                                    className="flex-none py-2"
                                                    onClick={() => {
                                                        onItemClick('month', value_moment.month() + 1);
                                                    }}
                                                >
                                                    {props.icon_next_month}
                                                </div>
                                                <div
                                                    className="flex-none py-2"
                                                    onClick={() => {
                                                        onItemClick('year', value_moment.year() + 1);
                                                    }}
                                                >
                                                    {props.icon_next_year}
                                                </div>
                                            </div>

                                            <div className={Styles.calendar_div}>
                                                <table>
                                                    <thead>
                                                        <tr>{calendar_headers}</tr>
                                                    </thead>
                                                    <tbody>{calendar_rows}</tbody>
                                                </table>
                                            </div>
                                        </div>
                                        {time_drop_list}
                                    </div>
                                    <div className="flex flex-row p-2">
                                        <div
                                            className="flex-none "
                                            onClick={() => {
                                                field.onChange(moment().format(props.outputFormat));
                                                if (!props.showTime) setVisible(false);
                                            }}
                                        >
                                            {props.showTime ? props.textNow : props.textToday}
                                        </div>
                                        <div className="flex-grow text-center"></div>
                                        <div className="flex-none ">Clear</div>
                                    </div>
                                </div>
                            </Popover.Panel>
                        </Transition>
                    </Popover>
                );
            }}
        />
    );
};

DateTimePicker.defaultProps = {
    beginningOfWeek: 7,
    outputFormat: 'YYYY-MM-DD HH:mm',
    topFormat: 'MMMM YYYY',
    generateList: null,
    timePortions: ['hour', 'minute'],
    showTime: true,
    week_titles: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
    textNow: 'Now',
    textToday: 'Today',
    fieldIcon: <GrCalendar className="absolute inset-y-0 right-0 flex items-center mr-3 pointer-events-none m-3" />,

    icon_last_year: <HiOutlineChevronDoubleLeft />,
    icon_last_month: <HiOutlineChevronLeft />,

    icon_next_month: <HiOutlineChevronRight />,
    icon_next_year: <HiOutlineChevronDoubleRight />,
};

export default DateTimePicker;
