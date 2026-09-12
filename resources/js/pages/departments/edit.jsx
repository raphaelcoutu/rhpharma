import AuthenticatedLayout from '@/layouts/authenticated-layout';
import Form from '@/pages/departments/form';
import { Head, useForm } from '@inertiajs/react';
import { update } from '@/routes/departments';

export default function Edit({ department, departmentTypes, workplaces }) {
    const { data, setData, put, errors } = useForm({
        name: department.name ?? '',
        description: department.description ?? '',
        department_type_id: String(department.department_type_id),
        workplace_id: String(department.workplace_id),
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(update(department.id));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Secteurs" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h2 className="text-2xl font-bold">Modifier un secteur</h2>
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="border-b border-gray-200 bg-white p-6">
                            <Form
                                data={data}
                                setData={setData}
                                onSubmit={handleSubmit}
                                errors={errors}
                                departmentTypes={departmentTypes}
                                workplaces={workplaces}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
