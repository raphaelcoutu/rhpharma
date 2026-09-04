import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { Building2, ChevronRight, MapPin, Plus, Search, Users } from 'lucide-react';
import { useMemo, useState } from 'react';

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function formatAddress(workplace) {
    return [workplace.address, workplace.city, workplace.province].filter(Boolean).join(', ');
}

export default function Index({ workplaces }) {
    const [search, setSearch] = useState('');
    const filteredWorkplaces = useMemo(() => {
        const query = normalize(search.trim());

        if (!query) {
            return workplaces;
        }

        return workplaces.filter((workplace) =>
            [workplace.name, workplace.code, workplace.address, workplace.city, workplace.province, workplace.country]
                .some((value) => normalize(value).includes(query)),
        );
    }, [search, workplaces]);

    const departmentCount = workplaces.reduce((total, workplace) => total + Number(workplace.departments_count ?? 0), 0);

    return (
        <AuthenticatedLayout>
            <Head title="Lieux de travail" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <Building2 className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Organisation</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Lieux de travail</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Gérez les établissements et les secteurs qui leur sont associés.
                            </p>
                        </div>
                        <SecondaryButton as={Link} href={route('workplaces.create')} className="gap-2">
                            <Plus className="h-4 w-4" />
                            Ajouter un lieu
                        </SecondaryButton>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-2">
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Lieux enregistrés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{workplaces.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <MapPin className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Secteurs associés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{departmentCount}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <Users className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Tous les lieux</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {filteredWorkplaces.length} résultat{filteredWorkplaces.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="relative w-full sm:max-w-xs">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input
                                    type="search"
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    placeholder="Rechercher un lieu..."
                                    aria-label="Rechercher un lieu"
                                    className="pl-9"
                                />
                            </div>
                        </div>

                        <Table>
                            <TableHeader>
                                <TableRow className="bg-gray-50/70 hover:bg-gray-50/70">
                                    <TableHead className="pl-6">Lieu</TableHead>
                                    <TableHead>Adresse</TableHead>
                                    <TableHead>Secteurs</TableHead>
                                    <TableHead className="pr-6 text-right">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredWorkplaces.map((workplace) => (
                                    <TableRow key={workplace.id}>
                                        <TableCell className="pl-6">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                                    <Building2 className="h-5 w-5" />
                                                </div>
                                                <div className="min-w-0">
                                                    <p className="truncate font-medium text-gray-900">{workplace.name}</p>
                                                    <span className="mt-1 inline-flex rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                                        {workplace.code}
                                                    </span>
                                                </div>
                                            </div>
                                        </TableCell>
                                        <TableCell className="min-w-56 text-gray-600">
                                            <p>{formatAddress(workplace)}</p>
                                            <p className="mt-1 text-xs text-gray-400">{workplace.country}</p>
                                        </TableCell>
                                        <TableCell>
                                            <span className="font-medium text-gray-900">{workplace.departments_count}</span>
                                        </TableCell>
                                        <TableCell className="pr-6 text-right">
                                            <Link
                                                href={route('workplaces.show', workplace.id)}
                                                className="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition hover:text-indigo-800"
                                            >
                                                Voir les secteurs
                                                <ChevronRight className="h-4 w-4" />
                                            </Link>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {filteredWorkplaces.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={4} className="px-6 py-12 text-center">
                                            <div className="mx-auto flex max-w-sm flex-col items-center">
                                                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                    <Search className="h-5 w-5" />
                                                </div>
                                                <p className="mt-4 font-medium text-gray-900">
                                                    {search ? 'Aucun lieu trouvé' : 'Aucun lieu de travail'}
                                                </p>
                                                <p className="mt-1 text-sm text-gray-500">
                                                    {search ? 'Essayez avec un autre terme de recherche.' : 'Commencez par ajouter votre premier lieu.'}
                                                </p>
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
