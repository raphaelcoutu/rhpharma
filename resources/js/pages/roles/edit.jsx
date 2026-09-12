import SecondaryButton from '@/components/secondary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/roles/form';
import { Head, Link, useForm } from '@inertiajs/react';
import { index, update } from '@/routes/roles';
import { ArrowLeft, ShieldCheck } from 'lucide-react';

export default function Edit({ role, permissions }) {
    const assignedPermissions = new Set((role.permissions ?? []).map((permission) => permission.code));
    const { data, setData, put, processing, errors } = useForm({
        name: role.name ?? '',
        description: role.description ?? '',
        permissions: Object.fromEntries(permissions.map((permission) => [permission.code, assignedPermissions.has(permission.code)])),
    });

    const handleSubmit = (event) => {
        event.preventDefault();
        put(update(role.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Modifier ${role.name}`} />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <Link
                        href={index()}
                        className="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-900"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Retour aux rôles et permissions
                    </Link>

                    <div className="flex items-start gap-4">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <ShieldCheck className="h-6 w-6" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight text-gray-900">Modifier un rôle</h1>
                            <p className="mt-1 text-sm text-gray-500">Mettez à jour le rôle « {role.name} » et ses permissions.</p>
                        </div>
                    </div>

                    <div className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="border-b border-gray-100 bg-gray-50/70 px-6 py-4 sm:px-8">
                            <h2 className="text-sm font-semibold text-gray-900">Informations du rôle</h2>
                            <p className="mt-1 text-sm text-gray-500">Les champs marqués comme requis doivent être complétés.</p>
                        </div>
                        <div className="p-6 sm:p-8">
                            <Form data={data} setData={setData} errors={errors} processing={processing} onSubmit={handleSubmit} />
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
