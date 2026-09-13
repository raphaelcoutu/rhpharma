import CalendarView from '@/components/calendar/calendar-view';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head } from '@inertiajs/react';

export default function Calendar(props) {
    return (
        <AuthenticatedLayout>
            <Head title={`Calendrier · ${props.schedule?.name ?? 'Horaire'}`} />
            <CalendarView {...props} />
        </AuthenticatedLayout>
    );
}
