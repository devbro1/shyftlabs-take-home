import { Subject } from 'rxjs';
import { filter } from 'rxjs/operators';

const alertSubject = new Subject();
const defaultId = 'default-alert';

export const alertService = {
    onAlert,
    success,
    error,
    info,
    warn,
    alert,
    clear,
};

export const alertType = {
    success: 'success',
    error: 'error',
    info: 'info',
    warning: 'warning',
};

// enable subscribing to alerts observable
function onAlert(id = defaultId) {
    return alertSubject.asObservable().pipe(
        filter((x: any) => {
            return x && x.id === id;
        }),
    );
}

// convenience methods
function success(message: string, options: any = {}) {
    alert({ ...options, type: alertType.success, message });
}

function error(message: string, options: any = {}) {
    alert({ ...options, type: alertType.error, message });
}

function info(message: string, options: any = {}) {
    alert({ ...options, type: alertType.info, message });
}

function warn(message: string, options: any = {}) {
    alert({ ...options, type: alertType.warning, message });
}

// core alert method
function alert(alert: any) {
    alert.id = alert.id || defaultId;
    alertSubject.next(alert);
}

// clear alerts
function clear(id = defaultId) {
    alertSubject.next({ id });
}
