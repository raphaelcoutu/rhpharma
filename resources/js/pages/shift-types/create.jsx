import SecondaryButton from '@/components/secondary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/shift-types/form';
import { Head, Link, useForm } from '@inertiajs/react';
import { index, store } from '@/routes/shiftTypes';
import { ArrowLeft, Clock3 } from 'lucide-react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        start_time: '',
        end_time: '',
    });

    const handleSubmit = (event) => {
        event.preventDefault();
        post(store());
    };

    return (
        <AuthenticatedLayout>
            <Head title="Nouveau type de shift" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <Link
                        href={index()}
                        className="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-900"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Retour aux types de shifts
                    </Link>

                    <div className="flex items-start gap-4">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <Clock3 className="h-6 w-6" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight text-gray-900">Créer un type de shift</h1>
                            <p className="mt-1 text-sm text-gray-500">Définissez le nom et les horaires de ce type de shift.</p>
                        </div>
                    </div>

                    <div className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="border-b border-gray-100 bg-gray-50/70 px-6 py-4 sm:px-8">
                            <h2 className="text-sm font-semibold text-gray-900">Informations du shift</h2>
                            <p className="mt-1 text-sm text-gray-500">Tous les champs sont obligatoires.</p>
                        </div>
                        <div className="p-6 sm:p-8">
                            <Form
                                data={data}
                                setData={setData}
                                errors={errors}
                                processing={processing}
                                onSubmit={handleSubmit}
                                submitLabel="Enregistrer le type"
                            />
                        </div>
                    </div>

                    <div className="flex justify-start">
                        <SecondaryButton as={Link} href={index()}>
                            Annuler
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
