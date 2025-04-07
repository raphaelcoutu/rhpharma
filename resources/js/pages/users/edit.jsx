import AuthenticatedLayout from '@/layouts/authenticated-layout.jsx';
import { Head, useForm } from '@inertiajs/react';
import Form from '@/pages/users/form.jsx';

export default function Edit({ user, branches, roles }) {

    const { data, setData, put, errors } = useForm({
        lastname: user.lastname ?? '',
        firstname: user.firstname ?? '',
        email: user.email ?? '',
        branch_id: user.branch_id,
        workdays_per_week: user.workdays_per_week,
        seniority: user.seniority ?? '',
        is_active: user.is_active,
        azure_id: user.azure_id ?? '',
        roles: user.roles.map(role => role.id)
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('users.update', user.id));
    };

    return <AuthenticatedLayout>
        <Head title="Utilisateurs"/>
        <div className="py-12">

            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 className="font-bold text-2xl">Modifier un utilisateur</h2>
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <Form data={data}
                              setData={setData}
                              branches={branches}
                              roles={roles}
                              errors={errors}
                              onSubmit={handleSubmit} />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>;
}
