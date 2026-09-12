import SecondaryButton from '@/components/secondary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/constraint-types/form';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Settings2 } from 'lucide-react';

export default function Edit({ constraintType }) {
    const { data, setData, put, processing, errors } = useForm({
        azure_id: constraintType.azure_id ?? '',
        name: constraintType.name ?? '',
        description: constraintType.description ?? '',
        code: constraintType.code ?? '',
        is_work: String(constraintType.is_work ?? ''),
        is_single_day: String(constraintType.is_single_day ?? ''),
        is_group_constraint: String(constraintType.is_group_constraint ?? ''),
        is_day_in_schedule: String(constraintType.is_day_in_schedule ?? ''),
    });

    const handleSubmit = (event) => {
        event.preventDefault();
        put(route('constraintTypes.update', constraintType.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Modifier un type de contrainte" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <Link
                        href={route('constraintTypes.index')}
                        className="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-900"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Retour aux types de contraintes
                    </Link>

                    <div className="flex items-start gap-4">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <Settings2 className="h-6 w-6" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight text-gray-900">Modifier un type de contrainte</h1>
                            <p className="mt-1 text-sm text-gray-500">Mettez à jour la règle « {constraintType.name} ».</p>
                        </div>
                    </div>

                    <div className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="border-b border-gray-100 bg-gray-50/70 px-6 py-4 sm:px-8">
                            <h2 className="text-sm font-semibold text-gray-900">Informations du type</h2>
                            <p className="mt-1 text-sm text-gray-500">Les champs marqués comme requis doivent être complétés.</p>
                        </div>
                        <div className="p-6 sm:p-8">
                            <Form
                                data={data}
                                setData={setData}
                                errors={errors}
                                processing={processing}
                                onSubmit={handleSubmit}
                                submitLabel="Enregistrer les modifications"
                            />
                        </div>
                    </div>

                    <div className="flex justify-start">
                        <SecondaryButton as={Link} href={route('constraintTypes.index')}>
                            Annuler
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
