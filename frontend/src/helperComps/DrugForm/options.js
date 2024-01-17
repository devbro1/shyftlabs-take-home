let rationale_code = [];
rationale_code.push({
    value: 1,
    title: '1: smart choice',
    description: 'Smart choice. This drug works well and is cost-effective and/or safer than other drugs.',
});
rationale_code.push({
    value: 2,
    title: '2: not cheapest',
    description: 'There are other drugs that work just as well and cost you less.',
});
rationale_code.push({
    value: 3,
    title: '3: cheaper OTC available',
    description: 'There are over-the-counter (OTC) drugs that work just as well and cost you less.',
});
rationale_code.push({
    value: 4,
    title: '4: better combo possible',
    description: 'You can take two or more drugs that together, work just as well and cost you less.',
});
rationale_code.push({
    value: 5,
    title: '5: generic option available',
    description: 'There is a generic version of this drug that works just as well and costs you less.',
});
rationale_code.push({
    value: 6,
    title: '6: better alternative available',
    description: 'There is another drug that works better and/or is safer.',
});
rationale_code.push({
    value: 7,
    title: '7: reformulary discounted',
    description: 'Reformulary members have access to this drug at the lowest co-pay.',
});
rationale_code.push({
    value: 8,
    title: '8: smart generic choice',
    description: 'Smart choice. This generic drug works just as well and costs you less.',
});
rationale_code.push({
    value: 9,
    title: '9: specialty drug',
    description:
        'This drug is considered a "specialty drug".  Specialty drugs are usually expensive, may need to be administered in a certain way, and often require ongoing assessments (appointments) to ensure the drug is working and to help manage side effects. There may',
});

let rg_tier = [];
rg_tier.push({ value: '1', title: '1' });
rg_tier.push({ value: '2', title: '2' });
rg_tier.push({ value: '3', title: '3' });
rg_tier.push({ value: 'Exclude', title: 'Exclude' });
rg_tier.push({ value: 'Restricted', title: 'Restricted' });
rg_tier.push({ value: 'SA', title: 'SA' });

let drug_product_type = [];
drug_product_type.push({ value: 'Brand', title: 'Brand' });
drug_product_type.push({ value: 'Biosimilar', title: 'Biosimilar' });
drug_product_type.push({ value: 'Generic', title: 'Generic' });

let drug_sub_type = [];
drug_sub_type.push({ value: 'MS Brand', title: 'MS Brand' });
drug_sub_type.push({ value: 'SS Brand', title: 'SS Brand' });
drug_sub_type.push({ value: 'Biosimilar', title: 'Biosimilar' });
drug_sub_type.push({ value: 'Generic', title: 'Generic' });

let options = {
    rationale_code: rationale_code,
    rg_tier: rg_tier,
    drug_product_type: drug_product_type,
    drug_sub_type: drug_sub_type,
};

export default options;
