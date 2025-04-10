import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/users/form';
import { Head, useForm } from '@inertiajs/react';

export default function Create({ auth, branches, roles }) {
    const { data, setData, post, errors } = useForm({
        lastname: '',
        firstname: '',
        email: '',
        branch_id: String(auth.user.branch_id),
        workdays_per_week: '5',
        seniority: '',
        is_active: true,
        azure_id: '',
        roles: [],
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('users.store'));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Utilisateurs" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h2 className="text-2xl font-bold">Créer un utilisateur</h2>
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
