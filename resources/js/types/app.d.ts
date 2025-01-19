declare namespace App {
    export type SharedData = {
        location: string;
        isWebpSupported: boolean;
        notification: NotificationData | null;
        timezones:  Timezone[],
    }

    export type Application = {
        id: number;
        title: string;
        url: string;
        company?: Company | null;
        salary_period: ApplicationSalaryPeriod;
        salary_type: ApplicationSalaryType;
        salary_min: number;
        salary_max: number;
        status: ApplicationStatus;
        notes?: string | null;
        applied_at: string;
        created_at: string;
        updated_at: string;
    }

    export type ApplicationSalaryPeriod = 'hourly' | 'daily' | 'weekly' | 'monthly' | 'yearly';

    export type ApplicationSalaryType = 'unknown' | 'range' | 'fixed';

    export type ApplicationStatus = 'applied' | 'awaiting_response' | 'interviewing' | 'rejected' | 'offer' | 'offer_accepted' | 'withdrawn';

    export type Company = {
      id: number;
      name: string;
      slug: string;
      applications: { data: Application[], meta: PageMeta };
      created_at: string;
      updated_at: string;
    }

    export type NotificationType =  'success' | 'error' | 'warning' | 'info' | 'default';

    export type NotificationData = {
        type: NotifcationType;
        body: string;
    }

    export type PageMeta = {
      current_page: number;
      from: number;
      last_page: number;
      per_page: number;
      to: number;
      total: number;
      path: string;
      links: {
        [key: number]: { url: string | null; label: string; active: boolean };
      }
    }

    export type Stats = {
      applied: number;
      interviewing: number;
      rejections: number;
      offers: number;
      withdrawn: number;
      awaiting: number;
    }
}
