import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head } from '@inertiajs/react';

export default function Home() {
    return (
        <AuthenticatedLayout>
            <Head title="Accueil" />
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="border-b border-gray-200 bg-white p-6">You're logged in!</div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
