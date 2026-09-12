import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { create, edit } from '@/routes/constraintTypes';
import { Check, Pencil, Plus, Search, Settings2, X } from 'lucide-react';
import { useMemo, useState } from 'react';

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function isEnabled(value) {
    return value === true || value === 1 || value === '1';
}

function BooleanBadge({ value }) {
    const enabled = isEnabled(value);

    return (
        <span
            className={`inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium ${
                enabled ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500'
            }`}
        >
            {enabled ? <Check className="h-3.5 w-3.5" /> : <X className="h-3.5 w-3.5" />}
            {enabled ? 'Oui' : 'Non'}
        </span>
    );
}

export default function Index({ constraintTypes }) {
    const [search, setSearch] = useState('');
    const filteredConstraintTypes = useMemo(() => {
        const query = normalize(search.trim());

        if (!query) {
            return constraintTypes;
        }

        return constraintTypes.filter((constraintType) =>
            [constraintType.name, constraintType.description, constraintType.code, constraintType.azure_id].some((value) =>
                normalize(value).includes(query),
            ),
        );
    }, [constraintTypes, search]);

    const workConstraintTypes = constraintTypes.filter((constraintType) => isEnabled(constraintType.is_work)).length;
    const groupConstraintTypes = constraintTypes.filter((constraintType) => isEnabled(constraintType.is_group_constraint)).length;

    return (
        <AuthenticatedLayout>
            <Head title="Types de contraintes" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <Settings2 className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Configuration</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Types de contraintes</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Gérez les règles utilisées pour construire et valider les horaires de votre branche.
                            </p>
                        </div>
                        <SecondaryButton as={Link} href={create()} className="gap-2">
                            <Plus className="h-4 w-4" />
                            Ajouter un type
                        </SecondaryButton>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-3">
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">Types configurés</p>
                            <p className="mt-2 text-3xl font-semibold text-gray-900">{constraintTypes.length}</p>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">Contraintes de travail</p>
                            <p className="mt-2 text-3xl font-semibold text-gray-900">{workConstraintTypes}</p>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">Selon disponibilité</p>
                            <p className="mt-2 text-3xl font-semibold text-gray-900">{groupConstraintTypes}</p>
                        </div>
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Tous les types de contraintes</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {filteredConstraintTypes.length} résultat{filteredConstraintTypes.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="relative w-full sm:max-w-xs">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input
                                    type="search"
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    placeholder="Rechercher un type..."
                                    aria-label="Rechercher un type de contrainte"
                                    className="pl-9"
                                />
                            </div>
                        </div>

                        <Table>
                            <TableHeader>
                                <TableRow className="bg-gray-50/70 hover:bg-gray-50/70">
                                    <TableHead className="pl-6">Nom</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Code</TableHead>
                                    <TableHead>Travail</TableHead>
                                    <TableHead>Une seule journée</TableHead>
                                    <TableHead>Disponibilité</TableHead>
                                    <TableHead>Journée à l&apos;horaire</TableHead>
                                    <TableHead>Critères</TableHead>
                                    <TableHead className="pr-6 text-right">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredConstraintTypes.map((constraintType) => (
                                    <TableRow key={constraintType.id}>
                                        <TableCell className="max-w-52 pl-6">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                                    <Settings2 className="h-5 w-5" />
                                                </div>
                                                <span className="font-medium text-gray-900">{constraintType.name}</span>
                                            </div>
                                        </TableCell>
                                        <TableCell className="max-w-64 text-sm text-gray-500">
                                            <span className="line-clamp-2">{constraintType.description || '—'}</span>
                                        </TableCell>
                                        <TableCell className="font-mono text-sm text-gray-600">{constraintType.code}</TableCell>
                                        <TableCell>
                                            <BooleanBadge value={constraintType.is_work} />
                                        </TableCell>
                                        <TableCell>
                                            <BooleanBadge value={constraintType.is_single_day} />
                                        </TableCell>
                                        <TableCell>
                                            <BooleanBadge value={constraintType.is_group_constraint} />
                                        </TableCell>
                                        <TableCell>
                                            <BooleanBadge value={constraintType.is_day_in_schedule} />
                                        </TableCell>
                                        <TableCell className="text-sm text-gray-600">{constraintType.criteria_count ?? 0}</TableCell>
                                        <TableCell className="pr-6 text-right">
                                            <Link
                                                href={edit(constraintType.id)}
                                                className="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition hover:text-indigo-800"
                                            >
                                                <Pencil className="h-4 w-4" />
                                                Modifier
                                            </Link>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {filteredConstraintTypes.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={9} className="px-6 py-12 text-center">
                                            <div className="mx-auto flex max-w-sm flex-col items-center">
                                                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                    <Search className="h-5 w-5" />
                                                </div>
                                                <p className="mt-4 font-medium text-gray-900">Aucun type trouvé</p>
                                                <p className="mt-1 text-sm text-gray-500">
                                                    {search ? 'Essayez une autre recherche.' : 'Ajoutez votre premier type de contrainte.'}
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
