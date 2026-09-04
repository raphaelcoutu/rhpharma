import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { Clock3, Pencil, Plus, Search } from 'lucide-react';
import { useMemo, useState } from 'react';

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function formatTime(value) {
    return value ? String(value).slice(0, 5) : '—';
}

export default function Index({ shiftTypes }) {
    const [search, setSearch] = useState('');
    const filteredShiftTypes = useMemo(() => {
        const query = normalize(search.trim());

        if (!query) {
            return shiftTypes;
        }

        return shiftTypes.filter((shiftType) =>
            [shiftType.name, shiftType.start_time, shiftType.end_time].some((value) => normalize(value).includes(query)),
        );
    }, [search, shiftTypes]);

    return (
        <AuthenticatedLayout>
            <Head title="Types de shifts" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <Clock3 className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Planification</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Types de shifts</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Configurez les horaires disponibles pour les shifts de votre branche.
                            </p>
                        </div>
                        <SecondaryButton as={Link} href={route('shiftTypes.create')} className="gap-2">
                            <Plus className="h-4 w-4" />
                            Ajouter un type
                        </SecondaryButton>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-2">
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Types configurés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{shiftTypes.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <Clock3 className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Résultats affichés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{filteredShiftTypes.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <Search className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Tous les types de shifts</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {filteredShiftTypes.length} résultat{filteredShiftTypes.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="relative w-full sm:max-w-xs">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input
                                    type="search"
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    placeholder="Rechercher un type..."
                                    aria-label="Rechercher un type de shift"
                                    className="pl-9"
                                />
                            </div>
                        </div>

                        <Table>
                            <TableHeader>
                                <TableRow className="bg-gray-50/70 hover:bg-gray-50/70">
                                    <TableHead className="pl-6">Nom</TableHead>
                                    <TableHead>Début</TableHead>
                                    <TableHead>Fin</TableHead>
                                    <TableHead className="pr-6 text-right">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredShiftTypes.map((shiftType) => (
                                    <TableRow key={shiftType.id}>
                                        <TableCell className="pl-6">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                                    <Clock3 className="h-5 w-5" />
                                                </div>
                                                <span className="font-medium text-gray-900">{shiftType.name}</span>
                                            </div>
                                        </TableCell>
                                        <TableCell className="font-mono text-sm text-gray-600">{formatTime(shiftType.start_time)}</TableCell>
                                        <TableCell className="font-mono text-sm text-gray-600">{formatTime(shiftType.end_time)}</TableCell>
                                        <TableCell className="pr-6 text-right">
                                            <Link
                                                href={route('shiftTypes.edit', shiftType.id)}
                                                className="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition hover:text-indigo-800"
                                            >
                                                <Pencil className="h-4 w-4" />
                                                Modifier
                                            </Link>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {filteredShiftTypes.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={4} className="px-6 py-12 text-center">
                                            <div className="mx-auto flex max-w-sm flex-col items-center">
                                                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                    <Search className="h-5 w-5" />
                                                </div>
                                                <p className="mt-4 font-medium text-gray-900">Aucun type trouvé</p>
                                                <p className="mt-1 text-sm text-gray-500">
                                                    {search ? 'Essayez une autre recherche.' : 'Ajoutez votre premier type de shift.'}
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
