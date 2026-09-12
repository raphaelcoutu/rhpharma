import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { KeyRound, Pencil, Search, ShieldCheck } from 'lucide-react';
import { useMemo, useState } from 'react';

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function PermissionBadge({ code }) {
    return <span className="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">{code}</span>;
}

export default function Index({ roles, permissions }) {
    const [search, setSearch] = useState('');
    const filteredRoles = useMemo(() => {
        const query = normalize(search.trim());

        if (!query) {
            return roles;
        }

        return roles.filter((role) =>
            [role.name, role.description, ...(role.permissions ?? []).map((permission) => permission.code)].some((value) =>
                normalize(value).includes(query),
            ),
        );
    }, [roles, search]);

    return (
        <AuthenticatedLayout>
            <Head title="Rôles et permissions" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div>
                        <div className="flex items-center gap-3 text-indigo-600">
                            <ShieldCheck className="h-5 w-5" />
                            <span className="text-sm font-semibold uppercase tracking-wider">Administration</span>
                        </div>
                        <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Rôles et permissions</h1>
                        <p className="mt-2 max-w-2xl text-sm text-gray-500">
                            Consultez les rôles disponibles et les permissions qui leur sont associées.
                        </p>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-3">
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">Rôles configurés</p>
                            <p className="mt-2 text-3xl font-semibold text-gray-900">{roles.length}</p>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">Permissions disponibles</p>
                            <p className="mt-2 text-3xl font-semibold text-gray-900">{permissions.length}</p>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">Résultats affichés</p>
                            <p className="mt-2 text-3xl font-semibold text-gray-900">{filteredRoles.length}</p>
                        </div>
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Tous les rôles</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {filteredRoles.length} résultat{filteredRoles.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="relative w-full sm:max-w-xs">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input
                                    type="search"
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    placeholder="Rechercher un rôle..."
                                    aria-label="Rechercher un rôle"
                                    className="pl-9"
                                />
                            </div>
                        </div>

                        <Table>
                            <TableHeader>
                                <TableRow className="bg-gray-50/70 hover:bg-gray-50/70">
                                    <TableHead className="pl-6">Rôle</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Permissions associées</TableHead>
                                    <TableHead className="pr-6 text-right">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredRoles.map((role) => (
                                    <TableRow key={role.id}>
                                        <TableCell className="pl-6 align-top">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                                    <KeyRound className="h-5 w-5" />
                                                </div>
                                                <span className="font-medium text-gray-900">{role.name}</span>
                                            </div>
                                        </TableCell>
                                        <TableCell className="max-w-64 align-top text-sm text-gray-500">
                                            <span className="line-clamp-2">{role.description || '—'}</span>
                                        </TableCell>
                                        <TableCell className="max-w-xl align-top">
                                            <div className="flex flex-wrap gap-2">
                                                {(role.permissions ?? []).length > 0 ? (
                                                    role.permissions.map((permission) => (
                                                        <PermissionBadge key={permission.code} code={permission.code} />
                                                    ))
                                                ) : (
                                                    <span className="text-sm text-gray-400">Aucune permission</span>
                                                )}
                                            </div>
                                        </TableCell>
                                        <TableCell className="pr-6 text-right align-top">
                                            <Link
                                                href={route('roles.edit', role.id)}
                                                className="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 transition hover:text-indigo-800"
                                            >
                                                <Pencil className="h-4 w-4" />
                                                Modifier
                                            </Link>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {filteredRoles.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={4} className="px-6 py-12 text-center">
                                            <div className="mx-auto flex max-w-sm flex-col items-center">
                                                <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                    <Search className="h-5 w-5" />
                                                </div>
                                                <p className="mt-4 font-medium text-gray-900">Aucun rôle trouvé</p>
                                                <p className="mt-1 text-sm text-gray-500">
                                                    {search ? 'Essayez une autre recherche.' : 'Aucun rôle n’est configuré pour le moment.'}
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
