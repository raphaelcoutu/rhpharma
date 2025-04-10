import AuthenticatedLayout from '@/layouts/authenticated-layout';
import DeleteUserForm from '@/pages/profile/partials/delete-user-form';
import UpdatePasswordForm from '@/pages/profile/partials/update-password-form';
import UpdateProfileInformation from '@/pages/profile/partials/update-profile-information-form';
import { Head } from '@inertiajs/react';

export default function Edit({ mustVerifyEmail, status }) {
    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Profile</h2>}>
            <Head title="Profile" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                        <UpdateProfileInformation mustVerifyEmail={mustVerifyEmail} status={status} className="max-w-xl" />
                    </div>

                    <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                        <UpdatePasswordForm className="max-w-xl" />
                    </div>

                    <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                        <DeleteUserForm className="max-w-xl" />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
