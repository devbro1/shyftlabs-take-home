import { useState, useEffect } from 'react';
import Alert from './Alert';
import { alertService } from './AlertService';
import { history } from 'data/browserhistory';

//https://flowbite.com/docs/components/alerts/
function __AlertsComp(props: any) {
    const [alerts, setAlerts] = useState<any[]>([]);

    useEffect(() => {
        history.listen(() => {
            // setAlerts([]);
        });
        const subscription = alertService.onAlert(props.id).subscribe((alert) => {
            //     // clear alerts when an empty alert is received
            //     // if (!alert.message) {
            setAlerts((a: any) => {
                const rc = [...a, alert];
                if (rc.length > 3) {
                    rc.pop();
                }
                return rc;
            });

            setTimeout(
                () => removeAlert(alert),
                parseInt(alert?.timeout || (import.meta.env.VITE_APP_NOTIFICATION_TIMEOUT as string)),
            );
            //     // // filter out alerts without 'keepAfterRouteChange' flag
            //     // const alerts = this.state.alerts.filter(x => x.keepAfterRouteChange);
            //     // // remove 'keepAfterRouteChange' flag on the rest
            //     // alerts.forEach(x => delete x.keepAfterRouteChange);
            //     // this.setState({ alerts });
            //     //     return;
            //     // }
            //     // add alert to array
            //     //this.setState({ alerts: [...this.state.alerts, alert] });
            //     // auto close alert if required
            //     if (alert.autoClose) {
            //         //setTimeout(() => this.removeAlert(alert), 3000);
            //     }
        });

        return () => subscription.unsubscribe();
    }, []);

    function removeAlert(alert: any) {
        const new_alerts = alerts.filter((x) => {
            return x !== alert;
        });
        setAlerts(new_alerts);
    }

    return (
        <div className="fixed top-0 left-0 right-0 z-40 w-96">
            {alerts.map((val, index) => {
                return (
                    <Alert
                        key={'alert-' + index}
                        {...val}
                        onClose={() => {
                            removeAlert(val);
                        }}
                    />
                );
            })}
        </div>
    );
}

export default __AlertsComp;
