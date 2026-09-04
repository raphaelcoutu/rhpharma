import SecondaryButton from '@/components/secondary-button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Building2, MapPin, Pencil, Plus } from 'lucide-react';

export default function Show({ workplace }) {
    return (
        <AuthenticatedLayout>
            <Head title={workplace.name} />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <Link
                        href={route('workplaces.index')}
                        className="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-900"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Tous les lieux de travail
                    </Link>

                    <div className="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                        <div className="flex items-start gap-4">
                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <Building2 className="h-6 w-6" />
                            </div>
                            <div>
                                <div className="flex flex-wrap items-center gap-2">
                                    <h1 className="text-3xl font-semibold tracking-tight text-gray-900">{workplace.name}</h1>
                                    <span className="rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-600">{workplace.code}</span>
                                </div>
                                <p className="mt-2 flex items-center gap-2 text-sm text-gray-500">
                                    <MapPin className="h-4 w-4" />
                                    {[workplace.address, workplace.city, workplace.province, workplace.country].filter(Boolean).join(', ')}
                                </p>
                            </div>
                        </div>
                        <SecondaryButton as={Link} href={route('departments.create')} className="gap-2">
                            <Plus className="h-4 w-4" />
                            Ajouter un secteur
                        </SecondaryButton>
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="border-b border-gray-100 px-5 py-5 sm:px-6">
                            <h2 className="font-semibold text-gray-900">Secteurs associés</h2>
                            <p className="mt-1 text-sm text-gray-500">
                                {workplace.departments.length} secteur{workplace.departments.length === 1 ? '' : 's'} rattaché{workplace.departments.length === 1 ? '' : 's'} à ce lieu.
                            </p>
                        </div>
                        <Table>
                            <TableHeader>
                                <TableRow className="bg-gray-50/70 hover:bg-gray-50/70">
                                    <TableHead className="pl-6">ID</TableHead>
                                    <TableHead>Nom</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Type</TableHead>
                                    <TableHead className="pr-6 text-right">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {workplace.departments.map((department) => (
                                    <TableRow key={department.id}>
                                        <TableCell className="pl-6 text-gray-500">{department.id}</TableCell>
                                        <TableCell className="font-medium text-gray-900">{department.name}</TableCell>
                                        <TableCell className="max-w-sm text-gray-600">{department.description || '—'}</TableCell>
                                        <TableCell className="text-gray-600">{department.department_type?.name ?? '—'}</TableCell>
                                        <TableCell className="pr-6 text-right">
                                            <Link
                                                href={route('departments.edit', department.id)}
                                                className="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition hover:text-indigo-800"
                                            >
                                                <Pencil className="h-4 w-4" />
                                                Modifier
                                            </Link>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {workplace.departments.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={5} className="px-6 py-12 text-center">
                                            <div className="mx-auto flex max-w-sm flex-col items-center">
                                                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                    <Building2 className="h-5 w-5" />
                                                </div>
                                                <p className="mt-4 font-medium text-gray-900">Aucun secteur associé</p>
                                                <p className="mt-1 text-sm text-gray-500">Ajoutez un secteur pour commencer à organiser ce lieu.</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
