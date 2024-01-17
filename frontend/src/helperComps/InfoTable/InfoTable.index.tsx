import { __InfoTableProps } from './InfoTable.types';
import { __Styles as Styles } from './InfoTable.styles';

// Data table component for list rendering
function __InfoTableComp(props: __InfoTableProps) {
    let title = null;
    if (props.title) {
        title = <div className={Styles.title}>{props.title}</div>;
    }
    return (
        <div className={Styles.root}>
            {title}
            <div className={Styles.scrollKeeper}>
                <table className={Styles.body}>
                    <tbody>
                        {props.data.map((row, index) => {
                            return (
                                <tr key={index} className={Styles.row}>
                                    <td className={Styles.fieldTitle}>{row.title}:</td>
                                    <td className={Styles.fieldValue}>{row.value}</td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
            </div>
        </div>
    );
}

__InfoTableComp.defaultProps = {
    data: [],
    title: '',
};

export default __InfoTableComp;
