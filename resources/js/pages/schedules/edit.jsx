import SecondaryButton from '@/components/secondary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/schedules/form';
import { Head, Link, useForm } from '@inertiajs/react';
import { index, update } from '@/routes/schedules';

function dateValue(value) {
    return String(value ?? '').slice(0, 10);
}

export default function Edit({ schedule }) {
    const { data, setData, put, processing, errors } = useForm({
        name: schedule.name ?? '',
        limit_date_weekends: dateValue(schedule.limit_date_weekends),
        limit_date: dateValue(schedule.limit_date),
        start_date: dateValue(schedule.start_date),
        end_date: dateValue(schedule.end_date),
    });

    function handleSubmit(event) {
        event.preventDefault();
        put(update(schedule.id));
    }

    return (
        <AuthenticatedLayout>
            <Head title="Modifier un horaire" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <div>
                        <p className="text-sm font-semibold uppercase tracking-wider text-indigo-600">Planification</p>
                        <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Modifier l&apos;horaire</h1>
                        <p className="mt-2 text-sm text-gray-500">Mettez à jour les dates de « {schedule.name} ».</p>
                    </div>

                    <section className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                        <Form
                            data={data}
                            errors={errors}
                            onSubmit={handleSubmit}
                            processing={processing}
                            setData={setData}
                            submitLabel="Enregistrer"
                        />
                    </section>

                    <SecondaryButton as={Link} href={index()}>
                        Retour à la liste
                    </SecondaryButton>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
