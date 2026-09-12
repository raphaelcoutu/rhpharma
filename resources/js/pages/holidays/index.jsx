import InputError from '@/components/input-error';
import PrimaryButton from '@/components/primary-button';
import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, useForm } from '@inertiajs/react';
import { store, update } from '@/routes/holidays';
import { ArrowDown, ArrowUp, ArrowUpDown, CalendarDays, Pencil, Plus, Search, X } from 'lucide-react';
import { useMemo, useState } from 'react';

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function getDateParts(value) {
    const match = String(value ?? '').match(/^(\d{4})-(\d{2})-(\d{2})/);

    if (!match) {
        return null;
    }

    return {
        year: Number(match[1]),
        month: Number(match[2]),
        day: Number(match[3]),
    };
}

function formatDateInput(value) {
    const parts = getDateParts(value);

    if (!parts) {
        return '';
    }

    return parts.year + '-' + String(parts.month).padStart(2, '0') + '-' + String(parts.day).padStart(2, '0');
}

function formatDate(value) {
    const parts = getDateParts(value);

    if (!parts) {
        return 'Date inconnue';
    }

    return new Intl.DateTimeFormat('fr-CA', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(parts.year, parts.month - 1, parts.day));
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
                aria-label={'Trier par ' + label}
            >
                {label}
                <SortIcon active={isActive} direction={direction} />
            </button>
        </th>
    );
}

export default function Index({ holidays = [] }) {
    const [search, setSearch] = useState('');
    const [sortKey, setSortKey] = useState('date');
    const [sortDirection, setSortDirection] = useState('asc');
    const [isFormVisible, setIsFormVisible] = useState(false);
    const [editingHoliday, setEditingHoliday] = useState(null);
    const [requestError, setRequestError] = useState('');

    const { data, setData, post, patch, processing, errors, clearErrors, reset } = useForm({
        description: '',
        date: '',
    });

    const filteredHolidays = useMemo(() => {
        const query = normalize(search.trim());

        if (!query) {
            return holidays;
        }

        return holidays.filter((holiday) =>
            [holiday.description, holiday.date, formatDate(holiday.date)].some((value) => normalize(value).includes(query)),
        );
    }, [holidays, search]);

    const sortedHolidays = useMemo(() => {
        return [...filteredHolidays].sort((firstHoliday, secondHoliday) => {
            const firstValue = sortKey === 'description' ? normalize(firstHoliday.description) : formatDateInput(firstHoliday.date);
            const secondValue = sortKey === 'description' ? normalize(secondHoliday.description) : formatDateInput(secondHoliday.date);
            const comparison = firstValue.localeCompare(secondValue, 'fr');

            return sortDirection === 'asc' ? comparison : -comparison;
        });
    }, [filteredHolidays, sortDirection, sortKey]);

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
        setEditingHoliday(null);
        setRequestError('');
        setIsFormVisible(true);
    }

    function showEditForm(holiday) {
        clearErrors();
        setData('description', holiday.description ?? '');
        setData('date', formatDateInput(holiday.date));
        setEditingHoliday(holiday);
        setRequestError('');
        setIsFormVisible(true);
    }

    function hideForm() {
        reset();
        clearErrors();
        setEditingHoliday(null);
        setRequestError('');
        setIsFormVisible(false);
    }

    function handleSubmit(event) {
        event.preventDefault();
        setRequestError('');

        const options = {
            only: ['holidays'],
            preserveScroll: true,
            onSuccess: hideForm,
            onError: (formErrors) => {
                if (Object.keys(formErrors).length === 0) {
                    setRequestError('Le jour férié n’a pas pu être enregistré.');
                }
            },
        };

        if (editingHoliday) {
            patch(update(editingHoliday.id), options);
            return;
        }

        post(store(), options);
    }

    return (
        <AuthenticatedLayout>
            <Head title="Jours fériés" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <CalendarDays className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Planification</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Jours fériés</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Préparez le calendrier des jours fériés qui seront pris en compte dans vos horaires.
                            </p>
                        </div>
                        {!isFormVisible && (
                            <SecondaryButton type="button" onClick={showCreateForm} className="gap-2">
                                <Plus className="h-4 w-4" />
                                Ajouter un jour férié
                            </SecondaryButton>
                        )}
                    </div>

                    <div className="grid gap-4 sm:grid-cols-3">
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Jours à venir</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{holidays.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                    <CalendarDays className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Prochain jour férié</p>
                                    <p className="mt-2 text-base font-semibold capitalize text-gray-900">
                                        {holidays[0] ? formatDate(holidays[0].date) : 'Aucun prévu'}
                                    </p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <CalendarDays className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-center justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">Résultats affichés</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{sortedHolidays.length}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                                    <Search className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {isFormVisible && (
                        <section className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                            <div className="flex items-start justify-between gap-4 border-b border-gray-100 pb-5">
                                <div>
                                    <h2 className="font-semibold text-gray-900">
                                        {editingHoliday ? 'Modifier le jour férié' : 'Ajouter un jour férié'}
                                    </h2>
                                    <p className="mt-1 text-sm text-gray-500">Indiquez le nom et la date du jour férié.</p>
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
                                <div className="grid gap-5 sm:grid-cols-[minmax(0,1fr)_220px]">
                                    <div className="grid gap-2">
                                        <Label htmlFor="holiday-description">Description</Label>
                                        <Input
                                            id="holiday-description"
                                            name="description"
                                            type="text"
                                            value={data.description}
                                            onChange={(event) => {
                                                setData('description', event.target.value);
                                                clearErrors('description');
                                            }}
                                            placeholder="Ex. Jour de l’An"
                                            autoFocus
                                            required
                                        />
                                        <InputError message={errors.description} />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="holiday-date">Date fériée</Label>
                                        <Input
                                            id="holiday-date"
                                            name="date"
                                            type="date"
                                            value={data.date}
                                            onChange={(event) => {
                                                setData('date', event.target.value);
                                                clearErrors('date');
                                            }}
                                            required
                                        />
                                        <InputError message={errors.date} />
                                    </div>
                                </div>
                                {requestError && (
                                    <p className="mt-3 text-sm text-red-600" role="alert">
                                        {requestError}
                                    </p>
                                )}
                                <div className="mt-6 flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                                    <SecondaryButton type="button" onClick={hideForm} disabled={processing}>
                                        Annuler
                                    </SecondaryButton>
                                    <PrimaryButton type="submit" disabled={processing}>
                                        {processing ? 'Enregistrement...' : editingHoliday ? 'Modifier' : 'Créer'}
                                    </PrimaryButton>
                                </div>
                            </form>
                        </section>
                    )}

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Tous les jours fériés</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {sortedHolidays.length} résultat{sortedHolidays.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="relative w-full sm:max-w-xs">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input
                                    type="search"
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    placeholder="Rechercher un jour..."
                                    aria-label="Rechercher un jour férié"
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
                                            label="Date"
                                            sortKey="date"
                                            activeSortKey={sortKey}
                                            direction={sortDirection}
                                            onSort={handleSort}
                                        />
                                        <SortableHeader
                                            label="Journée fériée"
                                            sortKey="description"
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
                                    {sortedHolidays.map((holiday) => (
                                        <tr key={holiday.id} className="border-b border-gray-100 transition last:border-0 hover:bg-gray-50/70">
                                            <td className="whitespace-nowrap px-4 py-4 pl-6">
                                                <div className="flex items-center gap-3">
                                                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                                        <CalendarDays className="h-5 w-5" />
                                                    </div>
                                                    <span className="font-medium capitalize text-gray-900">{formatDate(holiday.date)}</span>
                                                </div>
                                            </td>
                                            <td className="px-4 py-4 text-gray-600">{holiday.description}</td>
                                            <td className="whitespace-nowrap px-4 py-4 pr-6 text-right">
                                                <button
                                                    type="button"
                                                    onClick={() => showEditForm(holiday)}
                                                    className="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition hover:text-indigo-800"
                                                >
                                                    <Pencil className="h-4 w-4" />
                                                    Modifier
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                    {sortedHolidays.length === 0 && (
                                        <tr>
                                            <td colSpan="3" className="px-6 py-12 text-center">
                                                <div className="mx-auto flex max-w-sm flex-col items-center">
                                                    <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                        <Search className="h-5 w-5" />
                                                    </div>
                                                    <p className="mt-4 font-medium text-gray-900">Aucun jour trouvé</p>
                                                    <p className="mt-1 text-sm text-gray-500">
                                                        {search ? 'Essayez une autre recherche.' : 'Ajoutez votre premier jour férié.'}
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
