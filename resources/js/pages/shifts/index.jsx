import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { create, edit } from '@/routes/shifts';
import { Head, Link } from '@inertiajs/react';
import { ArrowDown, ArrowUp, ArrowUpDown, CalendarClock, Pencil, Plus, Search } from 'lucide-react';
import { useMemo, useState } from 'react';

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function shiftValue(shift, key) {
    if (key === 'department') {
        return shift.department?.name;
    }

    if (key === 'shiftType') {
        return shift.shift_type?.name;
    }

    return shift[key];
}

function SortIcon({ active, direction }) {
    if (!active) {
        return <ArrowUpDown className="h-4 w-4 text-gray-400" aria-hidden="true" />;
    }

    return direction === 'asc' ? (
        <ArrowUp className="h-4 w-4 text-indigo-600" aria-hidden="true" />
    ) : (
        <ArrowDown className="h-4 w-4 text-indigo-600" aria-hidden="true" />
    );
}

function SortableHeader({ label, sortKey, activeSortKey, direction, onSort }) {
    const isActive = activeSortKey === sortKey;

    return (
        <TableHead>
            <button
                type="button"
                className="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider transition hover:text-gray-900"
                onClick={() => onSort(sortKey)}
                aria-label={`Trier par ${label}`}
            >
                {label}
                <SortIcon active={isActive} direction={direction} />
            </button>
        </TableHead>
    );
}

export default function Index({ shifts = [] }) {
    const [search, setSearch] = useState('');
    const [sortKey, setSortKey] = useState('code');
    const [sortDirection, setSortDirection] = useState('asc');

    const filteredShifts = useMemo(() => {
        const query = normalize(search.trim());

        if (!query) {
            return shifts;
        }

        return shifts.filter((shift) =>
            [shift.code, shift.description, shift.department?.name, shift.shift_type?.name].some((value) => normalize(value).includes(query)),
        );
    }, [search, shifts]);

    const sortedShifts = useMemo(() => {
        return [...filteredShifts].sort((firstShift, secondShift) => {
            const firstValue = normalize(shiftValue(firstShift, sortKey));
            const secondValue = normalize(shiftValue(secondShift, sortKey));
            const comparison = firstValue.localeCompare(secondValue, 'fr');

            return sortDirection === 'asc' ? comparison : -comparison;
        });
    }, [filteredShifts, sortDirection, sortKey]);

    const departmentCount = new Set(shifts.map((shift) => shift.department?.id).filter(Boolean)).size;

    function handleSort(nextSortKey) {
        if (sortKey === nextSortKey) {
            setSortDirection((direction) => (direction === 'asc' ? 'desc' : 'asc'));
            return;
        }

        setSortKey(nextSortKey);
        setSortDirection('asc');
    }

    return (
        <AuthenticatedLayout>
            <Head title="Shifts" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <CalendarClock className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Planification</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Shifts</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Gérez les codes de shifts disponibles dans les départements de votre branche.
                            </p>
                        </div>
                        <SecondaryButton as={Link} href={create()} className="gap-2">
                            <Plus className="h-4 w-4" />
                            Ajouter un shift
                        </SecondaryButton>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-3">
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Shifts configurés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{shifts.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <CalendarClock className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Départements concernés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{departmentCount}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <CalendarClock className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Résultats affichés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{sortedShifts.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                                    <Search className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Tous les shifts</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {sortedShifts.length} résultat{sortedShifts.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="relative w-full sm:max-w-xs">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input
                                    type="search"
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    placeholder="Rechercher un shift..."
                                    aria-label="Rechercher un shift"
                                    className="pl-9"
                                />
                            </div>
                        </div>

                        <Table>
                            <TableHeader>
                                <TableRow className="bg-gray-50/70 hover:bg-gray-50/70">
                                    <SortableHeader
                                        label="Code"
                                        sortKey="code"
                                        activeSortKey={sortKey}
                                        direction={sortDirection}
                                        onSort={handleSort}
                                    />
                                    <SortableHeader
                                        label="Département"
                                        sortKey="department"
                                        activeSortKey={sortKey}
                                        direction={sortDirection}
                                        onSort={handleSort}
                                    />
                                    <SortableHeader
                                        label="Type de shift"
                                        sortKey="shiftType"
                                        activeSortKey={sortKey}
                                        direction={sortDirection}
                                        onSort={handleSort}
                                    />
                                    <TableHead className="pr-6 text-right">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {sortedShifts.map((shift) => (
                                    <TableRow key={shift.id}>
                                        <TableCell className="pl-6">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                                    <CalendarClock className="h-5 w-5" />
                                                </div>
                                                <div>
                                                    <p className="font-mono font-semibold text-gray-900">{shift.code}</p>
                                                    {shift.description && <p className="mt-1 text-xs text-gray-500">{shift.description}</p>}
                                                </div>
                                            </div>
                                        </TableCell>
                                        <TableCell className="text-gray-600">{shift.department?.name ?? '—'}</TableCell>
                                        <TableCell className="text-gray-600">{shift.shift_type?.name ?? '—'}</TableCell>
                                        <TableCell className="pr-6 text-right">
                                            <Link
                                                href={edit(shift.id)}
                                                className="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition hover:text-indigo-800"
                                            >
                                                <Pencil className="h-4 w-4" />
                                                Modifier
                                            </Link>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {sortedShifts.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={4} className="px-6 py-12 text-center">
                                            <div className="mx-auto flex max-w-sm flex-col items-center">
                                                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                    <Search className="h-5 w-5" />
                                                </div>
                                                <p className="mt-4 font-medium text-gray-900">
                                                    {search ? 'Aucun shift trouvé' : 'Aucun shift configuré'}
                                                </p>
                                                <p className="mt-1 text-sm text-gray-500">
                                                    {search
                                                        ? 'Essayez avec un autre terme de recherche.'
                                                        : 'Commencez par ajouter votre premier shift.'}
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
