import AuthenticatedLayout from '@/layouts/authenticated-layout.jsx';
import { Head, useForm } from '@inertiajs/react';
import Form from '@/pages/users/form.jsx';

export default function Create({ auth, branches, roles }) {

    const { data, setData, post, errors } = useForm({
        lastname: '',
        firstname: '',
        email: '',
        branch_id: auth.user.branch_id,
        workdays_per_week: 5,
        seniority: '',
        is_active: true,
        azure_id: '',
        roles: []
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('users.store'));
    };

    return <AuthenticatedLayout>
        <Head title="Utilisateurs"/>
        <div className="py-12">

            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 className="font-bold text-2xl">Créer un utilisateur</h2>
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <Form data={data}
                              setData={setData}
                              branches={branches}
                              roles={roles}
                              errors={errors}
                              onSubmit={handleSubmit}/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>;
}
