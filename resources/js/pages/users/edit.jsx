import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/users/form';
import { Head, useForm } from '@inertiajs/react';
import { update } from '@/routes/users';

export default function Edit({ user, branches, roles }) {
    const { data, setData, put, errors } = useForm({
        lastname: user.lastname ?? '',
        firstname: user.firstname ?? '',
        email: user.email ?? '',
        branch_id: String(user.branch_id),
        workdays_per_week: String(user.workdays_per_week),
        seniority: user.seniority ?? '',
        is_active: user.is_active,
        azure_id: user.azure_id ?? '',
        roles: user.roles.map((role) => role.id),
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(update(user.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Utilisateurs" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h2 className="text-2xl font-bold">Modifier un utilisateur</h2>
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="border-b border-gray-200 bg-white p-6">
                            <Form data={data} setData={setData} branches={branches} roles={roles} errors={errors} onSubmit={handleSubmit} />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
