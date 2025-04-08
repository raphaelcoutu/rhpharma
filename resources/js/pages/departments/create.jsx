import AuthenticatedLayout from '@/layouts/authenticated-layout.jsx';
import { Head, useForm } from '@inertiajs/react';
import Form from '@/pages/departments/form.jsx';

export default function Create({ auth, departmentTypes, workplaces }) {

    const {data, setData, post, errors} = useForm({
        name: '',
        description: '',
        department_type_id: '',
        workplace_id: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('departments.store'));
    };

    return <AuthenticatedLayout>
        <Head title="Utilisateurs"/>
        <div className="py-12">

            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 className="font-bold text-2xl">Créer un secteur</h2>
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 bg-white border-b border-gray-200">
                        <Form data={ data }
                              setData={ setData }
                              onSubmit={ handleSubmit }
                              errors={ errors }
                              departmentTypes={ departmentTypes }
                              workplaces={workplaces}
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>;
}
