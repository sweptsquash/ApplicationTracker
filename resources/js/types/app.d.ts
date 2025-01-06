declare namespace App {
    export type SharedData = {
        location: string;
        isWebpSupported: boolean;
        notification: NotificationData | null;
        timezones:  Timezone[],
    }

    export type Application = {
        id: number;
        date: string;
        title: string;
        role: string;
        salary: string | number;
        status: string;
    }

    export type NotificationType =  'success' | 'error' | 'warning' | 'info' | 'default';

    export type NotificationData = {
        type: NotifcationType;
        body: string;
    }
}
