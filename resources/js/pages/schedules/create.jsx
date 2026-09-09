import SecondaryButton from '@/components/secondary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/schedules/form';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        limit_date_weekends: '',
        limit_date: '',
        start_date: '',
        end_date: '',
    });

    function handleSubmit(event) {
        event.preventDefault();
        post(route('schedules.store'));
    }

    return (
        <AuthenticatedLayout>
            <Head title="Créer un horaire" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <div>
                        <p className="text-sm font-semibold uppercase tracking-wider text-indigo-600">Planification</p>
                        <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Créer un horaire</h1>
                        <p className="mt-2 text-sm text-gray-500">Définissez la période et les dates limites de validation.</p>
                    </div>

                    <section className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                        <Form
                            data={data}
                            errors={errors}
                            onSubmit={handleSubmit}
                            processing={processing}
                            setData={setData}
                            submitLabel="Créer l'horaire"
                        />
                    </section>

                    <SecondaryButton as={Link} href={route('schedules.index')}>
                        Retour à la liste
                    </SecondaryButton>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
