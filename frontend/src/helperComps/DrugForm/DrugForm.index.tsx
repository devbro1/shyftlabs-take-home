import { useEffect, useState } from 'react';
import {
    TextInputComp,
    SwitchComp,
    SelectComp,
    DateTimePickerComp,
    MultiSelect,
    TextAreaComp,
    ButtonComp,
} from 'utils';
import { DrugFormProps } from './DrugForm.types';
import { Styles } from 'styles';
import options from './options';
import _ from 'lodash';
import { DisorderApi } from 'api/DisorderApi';

function DrugForm(props: DrugFormProps) {
    const control = props.control;
    const styles: any = {};
    const { data: disordersOptions } = DisorderApi.options();
    const { data: allDisorders } = DisorderApi.getAll();

    _.forEach(props.descriptions, (_value, key) => {
        styles[key] = Styles.fields + ' ' + 'bg-blue-200';
    });

    const [rationaleCodeDescription, setRationalCodeDescription] = useState('');
    const rationale_code = props.watch('rationale_code');
    useEffect(() => {
        setRationalCodeDescription(
            options.rationale_code.filter((value) => {
                if (props.getValues) {
                    return value.value == props.getValues('rationale_code');
                }
                return false;
            })[0]?.description || '',
        );
    }, [rationale_code]);

    const disorders = props.watch('disorders');
    useEffect(() => {
        //find lowest used_for_code
        if (typeof disorders != 'object') {
            return;
        }

        if (props.getValues('used_for_code')) {
            return;
        }

        let lowest = 'z';
        disorders.map((disorder_id: any) => {
            const found = allDisorders?.find((d) => {
                if (d.id == disorder_id) {
                    return true;
                }
            });
            if (found && found.used_for_code < lowest) {
                lowest = found.used_for_code;
            }
        });

        if (lowest == 'z') {
            return;
        }

        props.setValue('used_for_code', lowest);
    }, [disorders]);

    useEffect(() => {
        if (props.getValues('drug_product_type') == 'Generic') {
            props.setValue('drug_sub_type', 'Generic');
        }
    }, [props.watch('drug_product_type')]);

    return (
        <>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.din || Styles.fields}
                    name="din"
                    control={control}
                    type="text"
                    title="din"
                    description={props.descriptions?.din}
                />
                <TextInputComp
                    className={styles.pin || Styles.fields}
                    name="pin"
                    control={control}
                    type="text"
                    title="pin"
                    description={props.descriptions?.pin}
                />
                <TextInputComp
                    className={styles.din_pin || Styles.fields}
                    name="din_pin"
                    control={control}
                    type="text"
                    title="din_pin"
                    description={props.descriptions?.din_pin}
                />
                <TextInputComp
                    className={styles.ref_id || Styles.fields}
                    name="ref_id"
                    control={control}
                    type="text"
                    title="ref_id"
                    description={props.descriptions?.ref_id}
                />
            </div>
            <hr className={Styles.spacer} />
            <h2 className={Styles.h2}>Name:</h2>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.drug_or_product_name || Styles.fields}
                    name="drug_or_product_name"
                    control={control}
                    type="text"
                    title="drug_or_product_name"
                    description={props.descriptions?.drug_or_product_name}
                />
                <TextInputComp
                    className={styles.health_canada_drug_name || Styles.fields}
                    name="health_canada_drug_name"
                    control={control}
                    type="text"
                    title="health_canada_drug_name"
                    description={props.descriptions?.health_canada_drug_name}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.active_ingredient || Styles.fields}
                    name="active_ingredient"
                    control={control}
                    type="text"
                    title="active_ingredient"
                    description={props.descriptions?.active_ingredient}
                />
                <SelectComp
                    className={styles.drug_product_type || Styles.fields}
                    name="drug_product_type"
                    control={control}
                    title="drug_product_type"
                    description={props.descriptions?.drug_product_type}
                    placeholder="please select one"
                    options={options.drug_product_type}
                />
                <SelectComp
                    className={styles.drug_sub_type || Styles.fields}
                    name="drug_sub_type"
                    control={control}
                    title="drug_sub_type"
                    description={props.descriptions?.drug_sub_type}
                    placeholder="please select one"
                    options={options.drug_sub_type}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.strength || Styles.fields}
                    name="strength"
                    control={control}
                    type="text"
                    title="strength"
                    description={props.descriptions?.strength}
                />
                <TextInputComp
                    className={styles.form || Styles.fields}
                    name="form"
                    control={control}
                    type="text"
                    title="form"
                    description={props.descriptions?.form}
                />
                <TextInputComp
                    className={styles.schedule || Styles.fields}
                    name="schedule"
                    control={control}
                    type="text"
                    title="schedule"
                    description={props.descriptions?.schedule}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.route_of_administration || Styles.fields}
                    name="route_of_administration"
                    control={control}
                    type="text"
                    title="route_of_administration"
                    description={props.descriptions?.route_of_administration}
                />
                <TextInputComp
                    className={styles.manufacturer || Styles.fields}
                    name="manufacturer"
                    control={control}
                    type="text"
                    title="manufacturer"
                    description={props.descriptions?.manufacturer}
                />
                <DateTimePickerComp
                    className={styles.discontinued_date || Styles.fields}
                    name="discontinued_date"
                    control={control}
                    showTime={false}
                    title="Discontinued Date"
                    outputFormat="YYYY-MM-DD"
                />
            </div>
            <div className={Styles.row}>
                <MultiSelect
                    options={disordersOptions}
                    className={styles.disorders || Styles.fields}
                    name="disorders"
                    control={control}
                    title="Disorders (Medical Condition)"
                    description={props.descriptions?.disorders}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.drug_class || Styles.fields}
                    name="drug_class"
                    control={control}
                    type="text"
                    title="drug_class"
                    description={props.descriptions?.drug_class}
                />
            </div>

            <div className={Styles.row}>
                <h2 className={Styles.h2}>Reformulary:</h2>
                <SwitchComp
                    className={styles.reformulary || Styles.switch_field}
                    name="reformulary"
                    title=""
                    control={control}
                    description={props.descriptions?.reformulary}
                />

                {props.setValue && props.getValues ? (
                    <ButtonComp
                        onClick={() => {
                            if (props.newEntry) {
                                props.setValue('slf_action', props.getValues('action'));
                                props.setValue('clic_action', props.getValues('action'));
                                props.setValue('cs_action', props.getValues('action'));
                                props.setValue('jg_action', props.getValues('action'));
                            }

                            if (props.getValues('gf_period') == 'indefinite') {
                                props.setValue('gf_period_code', '1');
                            }

                            if (['1', '2', '3'].indexOf(props.getValues('rg_tier')) >= 0) {
                                props.setValue('slf_tier', props.getValues('rg_tier'));
                                props.setValue('clic_tier', props.getValues('rg_tier'));
                                props.setValue('gwl_tier', props.getValues('rg_tier'));
                                props.setValue('gwl_cada_tier', props.getValues('rg_tier'));
                                props.setValue('cs_tier', props.getValues('rg_tier'));
                                props.setValue('cs_iw_tier', props.getValues('rg_tier'));
                                props.setValue('jg_tier', props.getValues('rg_tier'));

                                props.setValue('slf', true);
                                props.setValue('cs', true);
                                props.setValue('cs_iw', true);
                                props.setValue('clic', true);
                                props.setValue('gwl', true);
                                props.setValue('gwl_cada', true);
                                props.setValue('jg', true);

                                if (
                                    props.getValues('drug_product_type') == 'Generic' &&
                                    props.getValues('rg_tier') == '1'
                                ) {
                                    props.setValue('slf_rationale_code', '8');
                                    props.setValue('slf_visible_on_website', '1');
                                    props.setValue('slf_screencode', '1');

                                    props.setValue('clic_rationale_code', '8');
                                    props.setValue('clic_visible_on_website', '1');
                                    props.setValue('clic_screencode', '1');

                                    props.setValue('gwl_rationale_code', '8');
                                    props.setValue('gwl_visible_on_website', '1');
                                    props.setValue('gwl_screencode', '1');

                                    props.setValue('gwl_cada_rationale_code', '8');
                                    props.setValue('gwl_cada_visible_on_website', '1');
                                    props.setValue('gwl_cada_screencode', '1');

                                    props.setValue('cs_rationale_code', '8');
                                    props.setValue('cs_visible_on_website', '1');
                                    props.setValue('cs_screencode', '1');

                                    props.setValue('cs_iw_rationale_code', '8');
                                    props.setValue('cs_iw_visible_on_website', '1');
                                    props.setValue('cs_iw_screencode', '1');

                                    props.setValue('jg_rationale_code', '8');
                                    props.setValue('jg_visible_on_website', '1');
                                    props.setValue('jg_screencode', '1');
                                    props.setValue('visible_on_website', '1');
                                    props.setValue('newscreencode_1', '1');

                                    props.setValue('slf_gf_period', '');
                                    props.setValue('slf_gf_period_code', '');
                                    props.setValue('clic_gf_period', '');
                                    props.setValue('clic_gf_period_code', '');
                                    props.setValue('cs_gf_period', '');
                                    props.setValue('cs_gf_period_code', '');
                                    props.setValue('cs_iw_gf_period', '');
                                    props.setValue('cs_iw_gf_period_code', '');
                                    props.setValue('gwl_gf_period', '');
                                    props.setValue('gwl_gf_period_code', '');
                                    props.setValue('cs_iw_gf_period', '');
                                    props.setValue('cs_iw_gf_period_code', '');
                                    props.setValue('jg_gf_period', '');
                                    props.setValue('jg_gf_period_code', '');
                                    props.setValue('gf_period', '');
                                    props.setValue('gf_period_code', '');
                                }
                            } else if (props.getValues('rg_tier') == 'Exclude') {
                                props.setValue('slf_tier', 'None');
                                props.setValue('clic_tier', '3');
                                props.setValue('cs_tier', 'None');
                                props.setValue('cs_iw_tier', 'None');

                                props.setValue('slf', true);
                                props.setValue('cs', true);
                                props.setValue('cs_iw', true);
                                props.setValue('clic', true);
                            } else if (props.getValues('rg_tier') == 'SA') {
                                props.setValue('slf', props.getValues('reformulary'));
                                props.setValue('cs', props.getValues('reformulary'));
                                props.setValue('cs_iw', props.getValues('reformulary'));
                                props.setValue('clic', props.getValues('reformulary'));

                                props.setValue('slf_tier', 'SA');
                                props.setValue('slf_visible_on_website', '1');
                                props.setValue('slf_screencode', '40');
                                props.setValue('slf_rationale_code', '9');
                                props.setValue('slf_gf_period', 'indefinite');
                                props.setValue('slf_gf_period_code', '1');

                                props.setValue('clic_tier', '1');
                                props.setValue('sa', 'SA');
                                props.setValue('clic_gf_period', 'indefinite');
                                props.setValue('clic_gf_period_code', '1');
                                props.setValue('clic_screencode', '40');
                                props.setValue('clic_visible_on_website', '1');
                                props.setValue('clic_rationale_code', '9');

                                props.setValue('cs_tier', 'SA');
                                props.setValue('cs_visible_on_website', '1');
                                props.setValue('cs_screencode', '40');
                                props.setValue('cs_rationale_code', '9');
                                props.setValue('cs_gf_period', 'indefinite');
                                props.setValue('cs_gf_period_code', '1');

                                props.setValue('cs_iw_tier', 'SA');
                                props.setValue('cs_iw_visible_on_website', '1');
                                props.setValue('cs_iw_screencode', '40');
                                props.setValue('cs_iw_rationale_code', '9');
                                props.setValue('cs_iw_gf_period', 'indefinite');
                                props.setValue('cs_iw_gf_period_code', '1');

                                props.setValue('newscreencode_1', '40');
                                props.setValue('visible_on_website', '1');
                                props.setValue('rationale_code', '9');
                            }

                            props.setValue('jg_explanation', props.getValues('explanation'));
                            props.setValue('cs_external_notes', props.getValues('explanation'));
                            props.setValue('slf_external_notes', props.getValues('explanation'));
                            props.setValue('clic_external_notes', props.getValues('explanation'));

                            console.log(props.getValues('slf_visible_on_website'));
                            if (['', undefined].includes(props.getValues('slf_visible_on_website'))) {
                                props.setValue('slf_visible_on_website', '0');
                            }
                            if (['', undefined].includes(props.getValues('clic_visible_on_website'))) {
                                props.setValue('clic_visible_on_website', '0');
                            }
                            if (['', undefined].includes(props.getValues('gwl_visible_on_website'))) {
                                props.setValue('gwl_visible_on_website', '0');
                            }
                            if (['', undefined].includes(props.getValues('gwl_cada_visible_on_website'))) {
                                props.setValue('gwl_cada_visible_on_website', '0');
                            }
                            if (['', undefined].includes(props.getValues('cs_visible_on_website'))) {
                                props.setValue('cs_visible_on_website', '0');
                            }
                            if (['', undefined].includes(props.getValues('cs_iw_visible_on_website'))) {
                                props.setValue('cs_iw_visible_on_website', '0');
                            }
                            if (['', undefined].includes(props.getValues('jg_visible_on_website'))) {
                                props.setValue('jg_visible_on_website', '0');
                            }
                            if (['', undefined].includes(props.getValues('visible_on_website'))) {
                                props.setValue('visible_on_website', '0');
                            }
                        }}
                    >
                        Autofill All Providers
                    </ButtonComp>
                ) : (
                    ''
                )}
            </div>
            <div className={Styles.row}>
                <SelectComp
                    className={styles.rg_tier || Styles.fields}
                    name="rg_tier"
                    control={control}
                    title="rg_tier"
                    options={options.rg_tier}
                    placeholder="please select one"
                    description={props.descriptions?.rg_tier}
                />
                <TextInputComp
                    className={styles.action || Styles.fields}
                    name="action"
                    control={control}
                    type="text"
                    title="action"
                    description={props.descriptions?.action}
                />
                <TextInputComp
                    className={styles.explanation || Styles.fields}
                    name="explanation"
                    control={control}
                    type="text"
                    title="explanation"
                    description={props.descriptions?.explanation}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.gf_period || Styles.fields}
                    name="gf_period"
                    control={control}
                    type="text"
                    title="gf_period"
                    description={props.descriptions?.gf_period}
                />
                <TextInputComp
                    className={styles.quantity_limits || Styles.fields}
                    name="quantity_limits"
                    control={control}
                    type="text"
                    title="quantity_limits"
                    description={props.descriptions?.quantity_limits}
                />
                <TextInputComp
                    className={styles.step_therapy || Styles.fields}
                    name="step_therapy"
                    control={control}
                    type="text"
                    title="step_therapy"
                    description={props.descriptions?.step_therapy}
                />
                <TextInputComp
                    className={styles.specialty_drug || Styles.fields}
                    name="specialty_drug"
                    control={control}
                    type="text"
                    title="specialty_drug"
                    description={props.descriptions?.specialty_drug}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.gx_available_on_the_market || Styles.fields}
                    name="gx_available_on_the_market"
                    control={control}
                    type="text"
                    title="gx_available_on_the_market"
                    description={props.descriptions?.gx_available_on_the_market}
                />
                <TextInputComp
                    className={styles.patient_support_program || Styles.fields}
                    name="patient_support_program"
                    control={control}
                    type="text"
                    title="patient_support_program"
                    description={props.descriptions?.patient_support_program}
                />
                <TextInputComp
                    className={styles.special_distribution_program || Styles.fields}
                    name="special_distribution_program"
                    control={control}
                    type="text"
                    title="special_distribution_program"
                    description={props.descriptions?.special_distribution_program}
                />
            </div>
            <div className={Styles.row}>
                <TextAreaComp
                    className={styles.notes || Styles.fields}
                    name="notes"
                    control={control}
                    type="text"
                    title="notes"
                    description={props.descriptions?.notes}
                />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <h2 className={Styles.h2}>SLF:</h2>
                <SwitchComp
                    className={styles.slf || Styles.switch_field}
                    name="slf"
                    control={control}
                    description={props.descriptions?.slf}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.slf_tier || Styles.fields}
                    name="slf_tier"
                    control={control}
                    type="text"
                    title="slf_tier"
                    description={props.descriptions?.slf_tier}
                />
                <TextInputComp
                    className={styles.slf_action || Styles.fields}
                    name="slf_action"
                    control={control}
                    type="text"
                    title="slf_action"
                    description={props.descriptions?.slf_action}
                />
                <TextInputComp
                    className={styles.slf_external_notes || Styles.fields}
                    name="slf_external_notes"
                    control={control}
                    type="text"
                    title="SLF External Notes"
                    description={props.descriptions?.slf_external_notes}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.slf_ql || Styles.fields}
                    name="slf_ql"
                    control={control}
                    type="text"
                    title="slf_ql"
                    description={props.descriptions?.slf_ql}
                />
                <TextInputComp
                    className={styles.slf_gf_period || Styles.fields}
                    name="slf_gf_period"
                    control={control}
                    type="text"
                    title="slf_gf_period"
                    description={props.descriptions?.slf_gf_period}
                />
                <TextInputComp
                    className={styles.slf_gf_period_code || Styles.fields}
                    name="slf_gf_period_code"
                    control={control}
                    type="text"
                    title="slf_gf_period_code"
                    description={props.descriptions?.slf_gf_period_code}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.slf_screencode || Styles.fields}
                    name="slf_screencode"
                    control={control}
                    type="text"
                    title="slf_screencode"
                    description={props.descriptions?.slf_screencode}
                />
                <TextInputComp
                    className={styles.slf_visible_on_website || Styles.fields}
                    name="slf_visible_on_website"
                    control={control}
                    type="text"
                    title="slf_visible_on_website"
                    description={props.descriptions?.slf_visible_on_website}
                />
                <TextInputComp
                    className={styles.slf_rationale_code || Styles.fields}
                    name="slf_rationale_code"
                    control={control}
                    type="text"
                    title="slf_rationale_code"
                    description={props.descriptions?.slf_rationale_code}
                />
                <TextInputComp
                    className={styles.pin_slf || Styles.fields}
                    name="pin_slf"
                    control={control}
                    type="text"
                    title="pin_slf"
                    description={props.descriptions?.pin_slf}
                />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <h2 className={Styles.h2}>CLIC:</h2>
                <SwitchComp
                    className={styles.clic || Styles.switch_field}
                    name="clic"
                    control={control}
                    description={props.descriptions?.clic}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.clic_tier || Styles.fields}
                    name="clic_tier"
                    control={control}
                    type="text"
                    title="clic_tier"
                    description={props.descriptions?.clic_tier}
                />
                <TextInputComp
                    className={styles.sa || Styles.fields}
                    name="sa"
                    control={control}
                    type="text"
                    title="sa"
                    description={props.descriptions?.sa}
                />
                <TextInputComp
                    className={styles.clic_action || Styles.fields}
                    name="clic_action"
                    control={control}
                    type="text"
                    title="clic_action"
                    description={props.descriptions?.clic_action}
                />
                <TextInputComp
                    className={styles.clic_external_notes || Styles.fields}
                    name="clic_external_notes"
                    control={control}
                    type="text"
                    title="CLIC External Notes"
                    description={props.descriptions?.clic_external_notes}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.clic_ql || Styles.fields}
                    name="clic_ql"
                    control={control}
                    type="text"
                    title="clic_ql"
                    description={props.descriptions?.clic_ql}
                />
                <TextInputComp
                    className={styles.clic_gf_period || Styles.fields}
                    name="clic_gf_period"
                    control={control}
                    type="text"
                    title="clic_gf_period"
                    description={props.descriptions?.clic_gf_period}
                />
                <TextInputComp
                    className={styles.clic_gf_period_code || Styles.fields}
                    name="clic_gf_period_code"
                    control={control}
                    type="text"
                    title="clic_gf_period_code"
                    description={props.descriptions?.clic_gf_period_code}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.clic_screencode || Styles.fields}
                    name="clic_screencode"
                    control={control}
                    type="text"
                    title="clic_screencode"
                    description={props.descriptions?.clic_screencode}
                />
                <TextInputComp
                    className={styles.clic_visible_on_website || Styles.fields}
                    name="clic_visible_on_website"
                    control={control}
                    type="text"
                    title="clic_visible_on_website"
                    description={props.descriptions?.clic_visible_on_website}
                />
                <TextInputComp
                    className={styles.clic_rationale_code || Styles.fields}
                    name="clic_rationale_code"
                    control={control}
                    type="text"
                    title="clic_rationale_code"
                    description={props.descriptions?.clic_rationale_code}
                />
            </div>
            <div className={Styles.row}></div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <h2 className={Styles.h2}>Canada Life:</h2>
                <SwitchComp
                    className={styles.gwl || Styles.switch_field}
                    name="gwl"
                    control={control}
                    description={props.descriptions?.gwl}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.gwl_tier || Styles.fields}
                    name="gwl_tier"
                    control={control}
                    type="text"
                    title="Canada Life Tier"
                    description={props.descriptions?.gwl_tier}
                />
                <TextInputComp
                    className={styles.gwl_action || Styles.fields}
                    name="gwl_action"
                    control={control}
                    type="text"
                    title="Canada Life Action"
                    description={props.descriptions?.gwl_action}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.gwl_gf_period_code || Styles.fields}
                    name="gwl_gf_period_code"
                    control={control}
                    type="text"
                    title="Canada Life Grandfather Period Code"
                    description={props.descriptions?.gwl_gf_period_code}
                />

                <TextInputComp
                    className={styles.gwl_screencode || Styles.fields}
                    name="gwl_screencode"
                    control={control}
                    type="text"
                    title="Canada Life Screencode"
                    description={props.descriptions?.gwl_screencode}
                />
                <TextInputComp
                    className={styles.gwl_visible_on_website || Styles.fields}
                    name="gwl_visible_on_website"
                    control={control}
                    type="text"
                    title="Canada Life Visible on Website"
                    description={props.descriptions?.gwl_visible_on_website}
                />
                <TextInputComp
                    className={styles.gwl_rationale_code || Styles.fields}
                    name="gwl_rationale_code"
                    control={control}
                    type="text"
                    title="Canada Life Rationale Code"
                    description={props.descriptions?.gwl_rationale_code}
                />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <h2 className={Styles.h2}>Canada Life CADA:</h2>
                <SwitchComp
                    className={styles.gwl_cada || Styles.switch_field}
                    name="gwl_cada"
                    control={control}
                    description={props.descriptions?.gwl_cada}
                />
                {props.setValue && props.getValues ? (
                    <ButtonComp
                        onClick={() => {
                            props.setValue('gwl_cada', props.getValues('gwl'));
                            props.setValue('gwl_cada_tier', props.getValues('gwl_tier'));
                            props.setValue('gwl_cada_screencode', props.getValues('gwl_screencode'));
                            props.setValue('gwl_cada_visible_on_website', props.getValues('gwl_visible_on_website'));
                            props.setValue('gwl_cada_rationale_code', props.getValues('gwl_rationale_code'));
                        }}
                    >
                        Copy From CL
                    </ButtonComp>
                ) : (
                    ''
                )}
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.gwl_cada_tier || Styles.fields}
                    name="gwl_cada_tier"
                    control={control}
                    type="text"
                    title="cl_cada_tier"
                    description={props.descriptions?.gwl_cada_tier}
                />

                <TextInputComp
                    className={styles.gwl_cada_screencode || Styles.fields}
                    name="gwl_cada_screencode"
                    control={control}
                    type="text"
                    title="cl_cada_screencode"
                    description={props.descriptions?.gwl_cada_screencode}
                />
                <TextInputComp
                    className={styles.gwl_cada_visible_on_website || Styles.fields}
                    name="gwl_cada_visible_on_website"
                    control={control}
                    type="text"
                    title="cl_cada_visible_on_website"
                    description={props.descriptions?.gwl_cada_visible_on_website}
                />
                <TextInputComp
                    className={styles.gwl_cada_rationale_code || Styles.fields}
                    name="gwl_cada_rationale_code"
                    control={control}
                    type="text"
                    title="cl_cada_rationale_code"
                    description={props.descriptions?.gwl_cada_rationale_code}
                />
            </div>
            <div className={Styles.row}></div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <h2 className={Styles.h2}>CS:</h2>
                <SwitchComp
                    className={styles.cs || Styles.switch_field}
                    name="cs"
                    control={control}
                    description={props.descriptions?.cs}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.cs_tier || Styles.fields}
                    name="cs_tier"
                    control={control}
                    type="text"
                    title="cs_tier"
                    description={props.descriptions?.cs_tier}
                />
                <TextInputComp
                    className={styles.cs_action || Styles.fields}
                    name="cs_action"
                    control={control}
                    type="text"
                    title="cs_action"
                    description={props.descriptions?.cs_action}
                />
                <TextInputComp
                    className={styles.cs_external_notes || Styles.fields}
                    name="cs_external_notes"
                    control={control}
                    type="text"
                    title="CS External notes"
                    description={props.descriptions?.cs_external_notes}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.cs_gf_period || Styles.fields}
                    name="cs_gf_period"
                    control={control}
                    type="text"
                    title="cs_gf_period"
                    description={props.descriptions?.cs_gf_period}
                />

                <TextInputComp
                    className={styles.cs_gf_period_code || Styles.fields}
                    name="cs_gf_period_code"
                    control={control}
                    type="text"
                    title="cs_gf_period_code"
                    description={props.descriptions?.cs_gf_period_code}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.cs_screencode || Styles.fields}
                    name="cs_screencode"
                    control={control}
                    type="text"
                    title="cs_screencode"
                    description={props.descriptions?.cs_screencode}
                />
                <TextInputComp
                    className={styles.cs_visible_on_website || Styles.fields}
                    name="cs_visible_on_website"
                    control={control}
                    type="text"
                    title="cs_visible_on_website"
                    description={props.descriptions?.cs_visible_on_website}
                />
                <TextInputComp
                    className={styles.cs_rationale_code || Styles.fields}
                    name="cs_rationale_code"
                    control={control}
                    type="text"
                    title="cs_rationale_code"
                    description={props.descriptions?.cs_rationale_code}
                />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <h2 className={Styles.h2}>CS IW:</h2>
                <SwitchComp
                    className={styles.cs_iw || Styles.switch_field}
                    name="cs_iw"
                    control={control}
                    description={props.descriptions?.cs_iw}
                />
                {props.setValue && props.getValues ? (
                    <ButtonComp
                        onClick={() => {
                            props.setValue('cs', props.getValues('cs'));
                            props.setValue('cs_iw_tier', props.getValues('cs_tier'));
                            props.setValue('cs_iw_action', props.getValues('cs_action'));
                            props.setValue('cs_iw_gf_period', props.getValues('cs_gf_period'));
                            props.setValue('cs_gf_period_code', props.getValues('cs_gf_period_code'));
                            props.setValue('cs_iw_screencode', props.getValues('cs_screencode'));
                            props.setValue('cs_iw_visible_on_website', props.getValues('cs_visible_on_website'));
                            props.setValue('cs_iw_rationale_code', props.getValues('cs_rationale_code'));
                        }}
                    >
                        Copy From CS
                    </ButtonComp>
                ) : (
                    ''
                )}
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.cs_iw_tier || Styles.fields}
                    name="cs_iw_tier"
                    control={control}
                    type="text"
                    title="cs_iw_tier"
                    description={props.descriptions?.cs_iw_tier}
                />
                <TextInputComp
                    className={styles.cs_iw_action || Styles.fields}
                    name="cs_iw_action"
                    control={control}
                    type="text"
                    title="cs_iw_action"
                    description={props.descriptions?.cs_iw_action}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.cs_iw_gf_period || Styles.fields}
                    name="cs_iw_gf_period"
                    control={control}
                    type="text"
                    title="cs_iw_gf_period"
                    description={props.descriptions?.cs_iw_gf_period}
                />
                <TextInputComp
                    className={styles.cs_iw_gf_period_code || Styles.fields}
                    name="cs_iw_gf_period_code"
                    control={control}
                    type="text"
                    title="cs_iw_gf_period_code"
                    description={props.descriptions?.cs_iw_gf_period_code}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.cs_iw_screencode || Styles.fields}
                    name="cs_iw_screencode"
                    control={control}
                    type="text"
                    title="cs_iw_screencode"
                    description={props.descriptions?.cs_iw_screencode}
                />
                <TextInputComp
                    className={styles.cs_iw_visible_on_website || Styles.fields}
                    name="cs_iw_visible_on_website"
                    control={control}
                    type="text"
                    title="cs_iw_visible_on_website"
                    description={props.descriptions?.cs_iw_visible_on_website}
                />
                <TextInputComp
                    className={styles.cs_iw_rationale_code || Styles.fields}
                    name="cs_iw_rationale_code"
                    control={control}
                    type="text"
                    title="cs_iw_rationale_code"
                    description={props.descriptions?.cs_iw_rationale_code}
                />
            </div>
            <hr className={Styles.spacer} />
            <div className={Styles.row}>
                <h2 className={Styles.h2}>JG:</h2>
                <SwitchComp
                    className={styles.jg || Styles.switch_field}
                    name="jg"
                    control={control}
                    description={props.descriptions?.jg}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.jg_tier || Styles.fields}
                    name="jg_tier"
                    control={control}
                    type="text"
                    title="jg_tier"
                    description={props.descriptions?.jg_tier}
                />
                <TextInputComp
                    className={styles.jg_sa || Styles.fields}
                    name="jg_sa"
                    control={control}
                    type="text"
                    title="jg_sa"
                    description={props.descriptions?.jg_sa}
                />
                <TextInputComp
                    className={styles.jg_action || Styles.fields}
                    name="jg_action"
                    control={control}
                    type="text"
                    title="jg_action"
                    description={props.descriptions?.jg_action}
                />
                <TextInputComp
                    className={styles.jg_explanation || Styles.fields}
                    name="jg_explanation"
                    control={control}
                    type="text"
                    title="jg_explanation"
                    description={props.descriptions?.jg_explanation}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.jg_gf_period || Styles.fields}
                    name="jg_gf_period"
                    control={control}
                    type="text"
                    title="jg_gf_period"
                    description={props.descriptions?.jg_gf_period}
                />
                <TextInputComp
                    className={styles.jg_gf_period_code || Styles.fields}
                    name="jg_gf_period_code"
                    control={control}
                    type="text"
                    title="jg_gf_period_code"
                    description={props.descriptions?.jg_gf_period_code}
                />
                <TextInputComp
                    className={styles.jg_ql || Styles.fields}
                    name="jg_ql"
                    control={control}
                    type="text"
                    title="jg_ql"
                    description={props.descriptions?.jg_ql}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.jg_screencode || Styles.fields}
                    name="jg_screencode"
                    control={control}
                    type="text"
                    title="jg_screencode"
                    description={props.descriptions?.jg_screencode}
                />
                <TextInputComp
                    className={styles.jg_visible_on_website || Styles.fields}
                    name="jg_visible_on_website"
                    control={control}
                    type="text"
                    title="jg_visible_on_website"
                    description={props.descriptions?.jg_visible_on_website}
                />
                <TextInputComp
                    className={styles.jg_rationale_code || Styles.fields}
                    name="jg_rationale_code"
                    control={control}
                    type="text"
                    title="jg_rationale_code"
                    description={props.descriptions?.jg_rationale_code}
                />
                <TextInputComp
                    className={styles.jg_notes || Styles.fields}
                    name="jg_notes"
                    control={control}
                    type="text"
                    title="jg_notes"
                    description={props.descriptions?.jg_notes}
                />
            </div>
            <hr className={Styles.spacer} />
            <h2 className={Styles.h2}>Drug finder:</h2>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.generic_name || Styles.fields}
                    name="generic_name"
                    control={control}
                    type="text"
                    title="Generic Name"
                    description={props.descriptions?.generic_name}
                />
                <TextInputComp
                    className={styles.generic_version_of || Styles.fields}
                    name="generic_version_of"
                    control={control}
                    type="text"
                    title="generic_version_of"
                    description={props.descriptions?.generic_version_of}
                />
                <TextInputComp
                    className={styles.gf_period_code || Styles.fields}
                    name="gf_period_code"
                    control={control}
                    type="text"
                    title="gf_period_code"
                    description={props.descriptions?.gf_period_code}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.newscreencode_1 || Styles.fields}
                    name="newscreencode_1"
                    control={control}
                    type="text"
                    title="newscreencode_1"
                    description={props.descriptions?.newscreencode_1}
                />
                <TextInputComp
                    className={styles.visible_on_website || Styles.fields}
                    name="visible_on_website"
                    control={control}
                    type="text"
                    title="visible_on_website"
                    description={props.descriptions?.visible_on_website}
                />
                <SelectComp
                    className={styles.rationale_code || Styles.fields}
                    name="rationale_code"
                    control={control}
                    title="rationale_code"
                    options={options.rationale_code}
                    placeholder="please select one"
                    description={props.descriptions?.rationale_code || rationaleCodeDescription}
                />
                <TextInputComp
                    className={styles.used_for_code || Styles.fields}
                    name="used_for_code"
                    control={control}
                    type="text"
                    title="used_for_code"
                    description={props.descriptions?.used_for_code}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.blue_box || Styles.fields}
                    name="blue_box"
                    control={control}
                    type="text"
                    title="blue_box"
                    description={props.descriptions?.blue_box}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.alternative_dins || Styles.fields}
                    name="alternative_dins"
                    control={control}
                    type="text"
                    title="alternative_dins"
                    description={props.descriptions?.alternative_dins}
                />
                <TextInputComp
                    className={styles.alternative_dins_non_prescribed || Styles.fields}
                    name="alternative_dins_non_prescribed"
                    control={control}
                    type="text"
                    title="alternative_dins_non_prescribed"
                    description={props.descriptions?.alternative_dins_non_prescribed}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.quantity_limits_days || Styles.fields}
                    name="quantity_limits_days"
                    control={control}
                    type="text"
                    title="quantity_limits_days"
                    description={props.descriptions?.quantity_limits_days}
                />
                <TextInputComp
                    className={styles.vaccines_used_to_protect_against || Styles.fields}
                    name="vaccines_used_to_protect_against"
                    control={control}
                    type="text"
                    title="vaccines_used_to_protect_against"
                    description={props.descriptions?.vaccines_used_to_protect_against}
                />
                <TextInputComp
                    className={styles.vaccines_used_to_protect_against_french || Styles.fields}
                    name="vaccines_used_to_protect_against_french"
                    control={control}
                    type="text"
                    title="vaccines_used_to_protect_against_french"
                    description={props.descriptions?.vaccines_used_to_protect_against_french}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.modified || Styles.fields}
                    name="modified"
                    control={control}
                    type="text"
                    title="modified"
                    description={props.descriptions?.modified}
                />
                <TextInputComp
                    className={styles.alert_notifications || Styles.fields}
                    name="alert_notifications"
                    control={control}
                    type="text"
                    title="alert_notifications"
                    description={props.descriptions?.alert_notifications}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.drug_or_product_name_french || Styles.fields}
                    name="drug_or_product_name_french"
                    control={control}
                    type="text"
                    title="drug_or_product_name_french"
                    description={props.descriptions?.drug_or_product_name_french}
                />
                {/* <TextInputComp
                    className={styles.medical_condition_french || Styles.fields}
                    name="medical_condition_french"
                    control={control}
                    type="text"
                    title="medical_condition_french"
                    description={props.descriptions?.medical_condition_french}
                />
                <TextInputComp
                    className={styles.sub_medical_condition_french || Styles.fields}
                    name="sub_medical_condition_french"
                    control={control}
                    type="text"
                    title="sub_medical_condition_french"
                    description={props.descriptions?.sub_medical_condition_french}
                /> */}
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.drug_class_french || Styles.fields}
                    name="drug_class_french"
                    control={control}
                    type="text"
                    title="drug_class_french"
                    description={props.descriptions?.drug_class_french}
                />
                <TextInputComp
                    className={styles.active_ingredient_french || Styles.fields}
                    name="active_ingredient_french"
                    control={control}
                    type="text"
                    title="active_ingredient_french"
                    description={props.descriptions?.active_ingredient_french}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.generic_name_french || Styles.fields}
                    name="generic_name_french"
                    control={control}
                    type="text"
                    title="generic_name_french"
                    description={props.descriptions?.generic_name_french}
                />
                <TextInputComp
                    className={styles.generic_version_of_french || Styles.fields}
                    name="generic_version_of_french"
                    control={control}
                    type="text"
                    title="generic_version_of_french"
                    description={props.descriptions?.generic_version_of_french}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.blue_box_french || Styles.fields}
                    name="blue_box_french"
                    control={control}
                    type="text"
                    title="blue_box_french"
                    description={props.descriptions?.blue_box_french}
                />
                <TextInputComp
                    className={styles.alternative_dins_non_prescribed_french || Styles.fields}
                    name="alternative_dins_non_prescribed_french"
                    control={control}
                    type="text"
                    title="alternative_dins_non_prescribed_french"
                    description={props.descriptions?.alternative_dins_non_prescribed_french}
                />
            </div>
            <div className={Styles.row}>
                <TextInputComp
                    className={styles.strength_french || Styles.fields}
                    name="strength_french"
                    control={control}
                    type="text"
                    title="strength_french"
                    description={props.descriptions?.strength_french}
                />
                <TextInputComp
                    className={styles.form_french || Styles.fields}
                    name="form_french"
                    control={control}
                    type="text"
                    title="form_french"
                    description={props.descriptions?.form_french}
                />
            </div>
            {/* 
            <hr className={Styles.spacer} />
            <h2 className={Styles.h2}>To be removed fields:</h2>

            <TextInputComp
                className={styles.rationale || Styles.fields}
                name="rationale"
                control={control}
                type="text"
                title="rationale"
                description={props.descriptions?.rationale}
            />
            <TextInputComp
                className={styles.rationale_french || Styles.fields}
                name="rationale_french"
                control={control}
                type="text"
                title="rationale_french"
                description={props.descriptions?.rationale_french}
            />
            <TextInputComp
                className={styles.din_duplicate || Styles.fields}
                name="din_duplicate"
                control={control}
                type="text"
                title="din_duplicate"
                description={props.descriptions?.din_duplicate}
            />

            <SwitchComp
                className={styles.conditions || Styles.switch_field}
                name="conditions"
                title="conditions"
                control={control}
                description={props.descriptions?.conditions}
            />

            <TextInputComp
                className={styles.medical_condition || Styles.fields}
                name="medical_condition"
                control={control}
                type="text"
                title="medical_condition"
                description={props.descriptions?.medical_condition}
            />
            <TextInputComp
                className={styles.sub_medical_condition || Styles.fields}
                name="sub_medical_condition"
                control={control}
                type="text"
                title="sub_medical_condition"
                description={props.descriptions?.sub_medical_condition}
            />

            <TextInputComp
                className={styles.clic_targetted_letter || Styles.fields}
                name="clic_targetted_letter"
                control={control}
                type="text"
                title="clic_targetted_letter"
                description={props.descriptions?.clic_targetted_letter}
            />

            <TextInputComp
                className={styles.include_exclude || Styles.fields}
                name="include_exclude"
                control={control}
                type="text"
                title="include_exclude"
                description={props.descriptions?.include_exclude}
            />

            <TextInputComp
                className={styles.slfu || Styles.fields}
                name="slfu"
                control={control}
                type="text"
                title="slfu"
                description={props.descriptions?.slfu}
            />

            <TextInputComp
                className={styles.gwl_cada_action || Styles.fields}
                name="gwl_cada_action"
                control={control}
                type="text"
                title="gwl_cada_action"
                description={props.descriptions?.gwl_cada_action}
            />

            <TextInputComp
                className={styles.screen_code || Styles.fields}
                name="screen_code"
                control={control}
                type="text"
                title="screen_code"
                description={props.descriptions?.screen_code}
            />

            <TextInputComp
                className={styles.ramq || Styles.fields}
                name="ramq"
                control={control}
                type="text"
                title="ramq"
                description={props.descriptions?.ramq}
            />
            <TextInputComp
                className={styles.ahfs || Styles.fields}
                name="ahfs"
                control={control}
                type="text"
                title="ahfs"
                description={props.descriptions?.ahfs}
            />
            <TextInputComp
                className={styles.dc || Styles.fields}
                name="dc"
                control={control}
                type="text"
                title="dc"
                description={props.descriptions?.dc}
            />
            <TextInputComp
                className={styles.a_m || Styles.fields}
                name="a_m"
                control={control}
                type="text"
                title="a_m"
                description={props.descriptions?.a_m}
            />
            <TextInputComp
                className={styles.tc || Styles.fields}
                name="tc"
                control={control}
                type="text"
                title="tc"
                description={props.descriptions?.tc}
            />
            <TextInputComp
                className={styles.life_sustaining_otc || Styles.fields}
                name="life_sustaining_otc"
                control={control}
                type="text"
                title="life_sustaining_otc"
                description={props.descriptions?.life_sustaining_otc}
            />
            <TextInputComp
                className={styles.life_style_drug || Styles.fields}
                name="life_style_drug"
                control={control}
                type="text"
                title="life_style_drug"
                description={props.descriptions?.life_style_drug}
            />

            <TextInputComp
                className={styles.position_sur_reformulary || Styles.fields}
                name="position_sur_reformulary"
                control={control}
                type="text"
                title="position_sur_reformulary"
                description={props.descriptions?.position_sur_reformulary}
            />
            <TextInputComp
                className={styles.test_strip || Styles.fields}
                name="test_strip"
                control={control}
                type="text"
                title="test_strip"
                description={props.descriptions?.test_strip}
            />
            <TextInputComp
                className={styles.pin_clic || Styles.fields}
                name="pin_clic"
                control={control}
                type="text"
                title="pin_clic"
                description={props.descriptions?.pin_clic}
            />

            <TextInputComp
                className={styles.notes_on_rg_select || Styles.fields}
                name="notes_on_rg_select"
                control={control}
                type="text"
                title="notes_on_rg_select"
                description={props.descriptions?.notes_on_rg_select}
            />

            <TextInputComp
                className={styles.cs_notes || Styles.fields}
                name="cs_notes"
                control={control}
                type="text"
                title="cs_notes"
                description={props.descriptions?.cs_notes}
            />
            <TextInputComp
                className={styles.jg_notes || Styles.fields}
                name="jg_notes"
                control={control}
                type="text"
                title="jg_notes"
                description={props.descriptions?.jg_notes}
            />
            <TextInputComp
                className={styles.prexdu || Styles.fields}
                name="prexdu"
                control={control}
                type="text"
                title="PREX D"
                description={props.descriptions?.prexdu}
            /> */}
        </>
    );
}

DrugForm.defaultProps = {
    descriptions: {},
};

export default DrugForm;
