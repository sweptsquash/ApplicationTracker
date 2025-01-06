declare namespace App {
    export type SharedData = {
        location: string;
        isWebpSupported: boolean;
        notification: NotificationData | null;
        timezones:  Timezone[],
    }

    export type NotificationType =  'success' | 'error' | 'warning' | 'info' | 'default';

    export type NotificationData = {
        type: NotifcationType;
        body: string;
    }
}
