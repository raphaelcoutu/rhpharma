import InputError from '@/components/input-error';
import PrimaryButton from '@/components/primary-button';
import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, useForm } from '@inertiajs/react';
import { store as storeBranch, update as updateBranch } from '@/routes/branches';
import { ArrowDown, ArrowUp, ArrowUpDown, GitBranch, Pencil, Plus, Search, Users, X } from 'lucide-react';
import { useMemo, useState } from 'react';

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
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
        <th scope="col" className="h-11 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 first:pl-6 last:pr-6">
            <button
                type="button"
                className="inline-flex items-center gap-2 transition hover:text-gray-900"
                onClick={() => onSort(sortKey)}
                aria-label={`Trier par ${label}`}
            >
                {label}
                <SortIcon active={isActive} direction={direction} />
            </button>
        </th>
    );
}

export default function Index({ branches = [] }) {
    const [search, setSearch] = useState('');
    const [sortKey, setSortKey] = useState('name');
    const [sortDirection, setSortDirection] = useState('asc');
    const [isFormVisible, setIsFormVisible] = useState(false);
    const [editingBranch, setEditingBranch] = useState(null);
    const [requestError, setRequestError] = useState('');

    const { data, setData, post, put, processing, errors, clearErrors, reset } = useForm({ name: '' });

    const filteredBranches = useMemo(() => {
        const query = normalize(search.trim());

        if (!query) {
            return branches;
        }

        return branches.filter((branch) => normalize(branch.name).includes(query));
    }, [branches, search]);

    const sortedBranches = useMemo(() => {
        return [...filteredBranches].sort((firstBranch, secondBranch) => {
            const firstValue = sortKey === 'users_count' ? Number(firstBranch.users_count ?? 0) : normalize(firstBranch.name);
            const secondValue = sortKey === 'users_count' ? Number(secondBranch.users_count ?? 0) : normalize(secondBranch.name);
            const comparison = typeof firstValue === 'number' ? firstValue - secondValue : firstValue.localeCompare(secondValue, 'fr');

            return sortDirection === 'asc' ? comparison : -comparison;
        });
    }, [filteredBranches, sortDirection, sortKey]);

    const totalUsers = branches.reduce((total, branch) => total + Number(branch.users_count ?? 0), 0);

    function handleSort(nextSortKey) {
        if (sortKey === nextSortKey) {
            setSortDirection((direction) => (direction === 'asc' ? 'desc' : 'asc'));
            return;
        }

        setSortKey(nextSortKey);
        setSortDirection('asc');
    }

    function showCreateForm() {
        reset();
        clearErrors();
        setEditingBranch(null);
        setRequestError('');
        setIsFormVisible(true);
    }

    function showEditForm(branch) {
        clearErrors();
        setData('name', branch.name);
        setEditingBranch(branch);
        setRequestError('');
        setIsFormVisible(true);
    }

    function hideForm() {
        reset();
        clearErrors();
        setEditingBranch(null);
        setRequestError('');
        setIsFormVisible(false);
    }

    function handleSubmit(event) {
        event.preventDefault();
        setRequestError('');

        const options = {
            only: ['branches'],
            preserveScroll: true,
            onSuccess: hideForm,
            onError: (formErrors) => {
                if (Object.keys(formErrors).length === 0) {
                    setRequestError('La branche n’a pas pu être enregistrée.');
                }
            },
        };

        if (editingBranch) {
            put(updateBranch(editingBranch.id), options);
            return;
        }

        post(storeBranch(), options);
    }

    return (
        <AuthenticatedLayout>
            <Head title="Branches" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <GitBranch className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Administration</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Branches</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Gérez les branches et consultez le nombre d’utilisateurs associés à chacune.
                            </p>
                        </div>
                        {!isFormVisible && (
                            <SecondaryButton type="button" onClick={showCreateForm} className="gap-2">
                                <Plus className="h-4 w-4" />
                                Ajouter une branche
                            </SecondaryButton>
                        )}
                    </div>

                    <div className="grid gap-4 sm:grid-cols-3">
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Branches enregistrées</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{branches.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <GitBranch className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Résultats affichés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{sortedBranches.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <Search className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Utilisateurs associés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{totalUsers}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                                    <Users className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {isFormVisible && (
                        <section className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                            <div className="flex items-start justify-between gap-4 border-b border-gray-100 pb-5">
                                <div>
                                    <h2 className="font-semibold text-gray-900">{editingBranch ? 'Modifier la branche' : 'Ajouter une branche'}</h2>
                                    <p className="mt-1 text-sm text-gray-500">Le nom doit contenir au moins 3 caractères.</p>
                                </div>
                                <button
                                    type="button"
                                    onClick={hideForm}
                                    className="rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                                    aria-label="Fermer le formulaire"
                                >
                                    <X className="h-5 w-5" />
                                </button>
                            </div>
                            <form className="mt-5" onSubmit={handleSubmit}>
                                <div className="grid gap-2 sm:max-w-xl">
                                    <Label htmlFor="branch-name">Nom de la branche</Label>
                                    <Input
                                        id="branch-name"
                                        name="name"
                                        type="text"
                                        value={data.name}
                                        onChange={(event) => {
                                            setData('name', event.target.value);
                                            clearErrors('name');
                                        }}
                                        autoFocus
                                        required
                                    />
                                    <InputError message={errors.name} />
                                </div>
                                {requestError && (
                                    <p className="mt-2 text-sm text-red-600" role="alert">
                                        {requestError}
                                    </p>
                                )}
                                <div className="mt-6 flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                                    <SecondaryButton type="button" onClick={hideForm} disabled={processing}>
                                        Annuler
                                    </SecondaryButton>
                                    <PrimaryButton type="submit" disabled={processing}>
                                        {processing ? 'Enregistrement...' : editingBranch ? 'Modifier' : 'Créer'}
                                    </PrimaryButton>
                                </div>
                            </form>
                        </section>
                    )}

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Toutes les branches</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {sortedBranches.length} résultat{sortedBranches.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="relative w-full sm:max-w-xs">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input
                                    type="search"
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    placeholder="Rechercher une branche..."
                                    aria-label="Rechercher une branche"
                                    className="pl-9"
                                />
                            </div>
                        </div>

                        {requestError && !isFormVisible && (
                            <p className="border-b border-red-100 bg-red-50 px-5 py-3 text-sm text-red-700" role="alert">
                                {requestError}
                            </p>
                        )}

                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm">
                                <thead className="bg-gray-50/70">
                                    <tr>
                                        <SortableHeader
                                            label="Nom de la branche"
                                            sortKey="name"
                                            activeSortKey={sortKey}
                                            direction={sortDirection}
                                            onSort={handleSort}
                                        />
                                        <SortableHeader
                                            label="# Utilisateurs"
                                            sortKey="users_count"
                                            activeSortKey={sortKey}
                                            direction={sortDirection}
                                            onSort={handleSort}
                                        />
                                        <th
                                            scope="col"
                                            className="h-11 px-4 pr-6 text-right text-xs font-semibold uppercase tracking-wider text-gray-500"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {sortedBranches.map((branch) => (
                                        <tr key={branch.id} className="border-b border-gray-100 transition last:border-0 hover:bg-gray-50/70">
                                            <td className="whitespace-nowrap px-4 py-4 pl-6 font-medium text-gray-900">
                                                <div className="flex items-center gap-3">
                                                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                                        <GitBranch className="h-5 w-5" />
                                                    </div>
                                                    {branch.name}
                                                </div>
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-4 text-gray-600">{branch.users_count ?? 0}</td>
                                            <td className="whitespace-nowrap px-4 py-4 pr-6 text-right">
                                                <button
                                                    type="button"
                                                    onClick={() => showEditForm(branch)}
                                                    className="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition hover:text-indigo-800"
                                                >
                                                    <Pencil className="h-4 w-4" />
                                                    Modifier
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                    {sortedBranches.length === 0 && (
                                        <tr>
                                            <td colSpan="3" className="px-6 py-12 text-center">
                                                <div className="mx-auto flex max-w-sm flex-col items-center">
                                                    <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                        <Search className="h-5 w-5" />
                                                    </div>
                                                    <p className="mt-4 font-medium text-gray-900">Aucune branche trouvée</p>
                                                    <p className="mt-1 text-sm text-gray-500">
                                                        {search ? 'Essayez une autre recherche.' : 'Ajoutez votre première branche.'}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
