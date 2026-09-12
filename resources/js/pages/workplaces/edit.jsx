import SecondaryButton from '@/components/secondary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/workplaces/form';
import { Head, Link, useForm } from '@inertiajs/react';
import { show, update } from '@/routes/workplaces';
import { ArrowLeft, MapPin } from 'lucide-react';

export default function Edit({ workplace }) {
    const { data, setData, put, processing, errors } = useForm({
        name: workplace.name ?? '',
        code: workplace.code ?? '',
        address: workplace.address ?? '',
        city: workplace.city ?? '',
        province: workplace.province ?? '',
        country: workplace.country ?? '',
        postal_code: workplace.postal_code ?? '',
    });

    const handleSubmit = (event) => {
        event.preventDefault();
        put(update(workplace.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title={'Modifier ' + workplace.name} />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <Link
                        href={show(workplace.id)}
                        className="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-900"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Retour au lieu de travail
                    </Link>

                    <div className="flex items-start gap-4">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <MapPin className="h-6 w-6" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight text-gray-900">Modifier un lieu de travail</h1>
                            <p className="mt-1 text-sm text-gray-500">Mettez à jour les coordonnées de ce lieu de travail.</p>
                        </div>
                    </div>

                    <div className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="border-b border-gray-100 bg-gray-50/70 px-6 py-4 sm:px-8">
                            <h2 className="text-sm font-semibold text-gray-900">Informations du lieu</h2>
                            <p className="mt-1 text-sm text-gray-500">Tous les champs sont obligatoires.</p>
                        </div>
                        <div className="p-6 sm:p-8">
                            <Form data={data} setData={setData} errors={errors} processing={processing} onSubmit={handleSubmit} />
                        </div>
                    </div>

                    <div className="flex justify-start">
                        <SecondaryButton as={Link} href={show(workplace.id)}>
                            Annuler
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
